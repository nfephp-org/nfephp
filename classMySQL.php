<?php
/**
 * Ultimate MySQL Wrapper Class
 *
 * @version 2.4.1
 * @author Jeff L. Williams
 * @link http://www.phpclasses.org/ultimatemysql
 *
 * Contributions from
 *   Frank P. Walentynowicz
 *   Larry Wakeman
 *   Nicola Abbiuso
 *   Douglas Gintz
 */
class MySQL {

	// SETE ESTES VALORES PARA SUA CONEXAO
	private $db_host    = "localhost";  // nome do servidor
	private $db_user    = "admin";       // nome do usuario
	private $db_pass    = "forever";           // senha
	private $db_dbname  = "intranet";           // nome da base de dados
	private $db_charset = "latin1";           // optional character set (ex. utf8)
	private $db_pcon    = false;        // usar uma conexao persistente ?

	// constantes para a funçao SQLValue
	const SQLVALUE_BIT      = "bit";
	const SQLVALUE_BOOLEAN  = "boolean";
	const SQLVALUE_DATE     = "date";
	const SQLVALUE_DATETIME = "datetime";
	const SQLVALUE_NUMBER   = "number";
	const SQLVALUE_T_F      = "t-f";
	const SQLVALUE_TEXT     = "text";
	const SQLVALUE_TIME     = "time";
	const SQLVALUE_Y_N      = "y-n";

	// variaveis internas da classe - NAO ALTERE
	private $active_row     = -1;       // linha atual
	private $error_desc     = "";       // descricao do ultimo erro mysql
	private $error_number   = 0;        // numeor do ultimo erro mysql
	private $in_transaction = false;    // usado para transactions
	private $last_insert_id;            // ultimo id de registro inserido
	private $last_result;               // ultimo resultado de uma query mysql
	private $last_sql       = "";       // ultima query mysql
	private $mysql_link     = 0;        // mysql link resource
	private $time_diff      = 0;        // registro da diferença de tempo
	private $time_start     = 0;        // tempo de inicio do timer

	/**
	 * Determina se erro causou uma execao
	 *
	 * @var boolean Seta para true quando ocorre uma execao
	 */
	public $ThrowExceptions = false;

	/**
	 * Construtor: Abre a conexao com a base de dados
	 *
	 * @param boolean $connect (Optional) Auto-conecta quando o objeto e criado
	 * @param string $database (Optional) Nome da base de dados
	 * @param string $server   (Optional) Nome do Host
	 * @param string $username (Optional) Nome do usuario
	 * @param string $password (Optional) Password
	 * @param string $charset  (Optional) Character set
	 */
	public function __construct($connect=true, $database="", $server="", $username="", $password="", $charset="") {
		if (strlen($database) > 0) $this->db_dbname  = $database;
		if (strlen($server)   > 0) $this->db_host    = $server;
		if (strlen($username) > 0) $this->db_user    = $username;
		if (strlen($password) > 0) $this->db_pass    = $password;
		if (strlen($charset)  > 0) $this->db_charset = $charset;

		if (strlen($this->db_host) > 0 &&
			strlen($this->db_user) > 0 &&
			strlen($this->db_pass) > 0) {
			if ($connect) $this->Open();
		}
	}

	/**
	 * Destrutor: Fecha a conexao com a base de dados
	 *
	 */
	public function __destruct() {
		$this->Close();
	}

	/**
	 * Automaticacamente faz um INSERT ou UPDATE dependendo da existencia do registro
     * na tablea
	 *
	 * @param string $tableName Nome da tabela
	 * @param array $valuesArray Uma matriz associativa contendo os nomes das colunas
	 *                           como chaves e os valores como dados.
     *                           Os valores devem ser legiveis pelo SQL com aspas
	 *                           envolvendo strings, datas formatadas, etc
	 *                            
	 * @param array $whereArray Uma matris associativa contendo os nomes das colunas
	 *                           com chaves e os alores como dados.
	 * @return boolean Retorna TRUE se successo ou FALSE se erro
	 */
	public function AutoInsertUpdate($tableName, $valuesArray, $whereArray) {
		$this->ResetError();
		$this->SelectRows($tableName, $whereArray);
		if (! $this->Error()) {
			if ($this->HasRecords()) {
				return $this->UpdateRows($tableName, $valuesArray, $whereArray);
			} else {
				return $this->InsertRow($tableName, $valuesArray);
			}
		} else {
			return false;
		}
	}

	/**
	 * Retorna true se o ponteiro interno esta no inicio dos registros
	 *
	 * @return boolean TRUE se for a prinmeira linha ou FALSE se nao
	 */
	public function BeginningOfSeek() {
		$this->ResetError();
		if ($this->IsConnected()) {
			if ($this->active_row < 1) {
				return true;
			} else {
				return false;
			}
		} else {
			$this->SetError("No connection");
			return false;
		}
	}

	/**
	 * [STATIC] Constroi uma lista de colunas delimetadas com virgulas para uso no SQL
	 *
	 * @param array $valuesArray Uma matriz contendo do nomas das colunas.
	 * @param boolean $addQuotes (Optional) TRUE para adicionar aspas
	 * @param boolean $showAlias (Optional) TRUE para mostrar os apelidos das colunas
	 * @return string Retorna lista de colunas em SQL
	 */
	static private function BuildSQLColumns($columns, $addQuotes = true, $showAlias = true) {
		if ($addQuotes) {
			$quote = "`";
		} else {
			$quote = "";
		}
		switch (gettype($columns)) {
			case "array":
				$sql = "";
				foreach ($columns as $key => $value) {
					// Build the columns
					if (strlen($sql) == 0) {
						$sql = $quote . $value . $quote;
					} else {
						$sql .= ", " . $quote . $value . $quote;
					}
					if ($showAlias && is_string($key) && (! empty($key))) {
						$sql .= ' AS "' . $key . '"';
					}
				}
				return $sql;
				break;
			case "string":
				return $quote . $columns . $quote;
				break;
			default:
				return false;
				break;
		}
	}

	/**
	 * [STATIC] Constroi o comando SQL para DELETE
	 *
	 * @param string $tableName O nome da tabela
	 * @param array $whereArray (Optional) Uma matriz associativa contendo os
	 *                           nomes das colunas como chaves e os valores como dados
	 *                           Caso nada seja especificado todos os registros da tabela
	 *                           serao apagados.
	 * @return string Retorna o comando SQL DELETE
	 */
	static public function BuildSQLDelete($tableName, $whereArray = null) {
		$sql = "DELETE FROM `" . $tableName . "`";
		if (! is_null($whereArray)) {
			$sql .= self::BuildSQLWhereClause($whereArray);
		}
		return $sql;
	}

	/**
	 * [STATIC] Builds a SQL INSERT statement
	 *
	 * @param string $tableName The name of the table
	 * @param array $valuesArray An associative array containing the column
	 *                            names as keys and values as data. The values
	 *                            must be SQL ready (i.e. quotes around
	 *                            strings, formatted dates, ect)
	 * @return string Returns a SQL INSERT statement
	 */
	static public function BuildSQLInsert($tableName, $valuesArray) {
		$columns = self::BuildSQLColumns(array_keys($valuesArray));
		$values  = self::BuildSQLColumns($valuesArray, false, false);
		$sql = "INSERT INTO `" . $tableName .
			   "` (" . $columns . ") VALUES (" . $values . ")";
		return $sql;
	}

	/**
	 * Builds a simple SQL SELECT statement
	 *
	 * @param string $tableName The name of the table
	 * @param array $whereArray (Optional) An associative array containing the
	 *                          column names as keys and values as data. The
	 *                          values must be SQL ready (i.e. quotes around
	 *                          strings, formatted dates, ect)
	 * @param array/string $columns (Optional) The column or list of columns to select
	 * @param array/string $sortColumns (Optional) Column or list of columns to sort by
	 * @param boolean $sortAscending (Optional) TRUE for ascending; FALSE for descending
	 *                               This only works if $sortColumns are specified
	 * @param integer/string $limit (Optional) The limit of rows to return
	 * @return string Returns a SQL SELECT statement
	 */
	static public function BuildSQLSelect($tableName, $whereArray = null, $columns = null,
										  $sortColumns = null, $sortAscending = true, $limit = null) {
		if (! is_null($columns)) {
			$sql = self::BuildSQLColumns($columns);
		} else {
			$sql = "*";
		}
		$sql = "SELECT " . $sql . " FROM `" . $tableName . "`";
		if (is_array($whereArray)) {
			$sql .= self::BuildSQLWhereClause($whereArray);
		}
		if (! is_null($sortColumns)) {
			$sql .= " ORDER BY " .
					self::BuildSQLColumns($sortColumns, true, false) .
					" " . ($sortAscending ? "ASC" : "DESC");
		}
		if (! is_null($limit)) {
			$sql .= " LIMIT " . $limit;
		}
		return $sql;
	}

	/**
	 * [STATIC] Builds a SQL UPDATE statement
	 *
	 * @param string $tableName The name of the table
	 * @param array $valuesArray An associative array containing the column
	 *                            names as keys and values as data. The values
	 *                            must be SQL ready (i.e. quotes around
	 *                            strings, formatted dates, ect)
	 * @param array $whereArray (Optional) An associative array containing the
	 *                           column names as keys and values as data. The
	 *                           values must be SQL ready (i.e. quotes around
	 *                           strings, formatted dates, ect). If not specified
	 *                           then all values in the table are updated.
	 * @return string Returns a SQL UPDATE statement
	 */
	static public function BuildSQLUpdate($tableName, $valuesArray, $whereArray = null) {
		$sql = "";
		foreach ($valuesArray as $key => $value) {
			if (strlen($sql) == 0) {
				$sql = "`" . $key . "` = " . $value;
			} else {
				$sql .= ", `" . $key . "` = " . $value;
			}
		}
		$sql = "UPDATE `" . $tableName . "` SET " . $sql;
		if (is_array($whereArray)) {
			$sql .= self::BuildSQLWhereClause($whereArray);
		}
		return $sql;
	}

	/**
	 * [STATIC] Builds a SQL WHERE clause from an array.
	 * If a key is specified, the key is used at the field name and the value
	 * as a comparison. If a key is not used, the value is used as the clause.
	 *
	 * @param array $whereArray An associative array containing the column
	 *                           names as keys and values as data. The values
	 *                           must be SQL ready (i.e. quotes around
	 *                           strings, formatted dates, ect)
	 * @return string Returns a string containing the SQL WHERE clause
	 */
	static public function BuildSQLWhereClause($whereArray) {
		$where = "";
		foreach ($whereArray as $key => $value) {
			if (strlen($where) == 0) {
				if (is_string($key)) {
					$where = " WHERE `" . $key . "` = " . $value;
				} else {
					$where = " WHERE " . $value;
				}
			} else {
				if (is_string($key)) {
					$where .= " AND `" . $key . "` = " . $value;
				} else {
					$where .= " AND " . $value;
				}
			}
		}
		return $where;
	}

	/**
	 * Close current MySQL connection
	 *
	 * @return object Returns TRUE on success or FALSE on error
	 */
	public function Close() {
		$this->ResetError();
		$this->active_row = -1;
		$success = $this->Release();
		if ($success) {
			$success = @mysql_close($this->mysql_link);
			if (! $success) {
				$this->SetError();
			} else {
				unset($this->last_sql);
				unset($this->last_result);
				unset($this->mysql_link);
			}
		}
		return $success;
	}

	/**
	 * Deletes rows in a table based on a WHERE filter
	 * (can be just one or many rows based on the filter)
	 *
	 * @param string $tableName The name of the table
	 * @param array $whereArray (Optional) An associative array containing the
	 *                          column names as keys and values as data. The
	 *                          values must be SQL ready (i.e. quotes around
	 *                          strings, formatted dates, ect). If not specified
	 *                          then all values in the table are deleted.
	 * @return boolean Returns TRUE on success or FALSE on error
	 */
	public function DeleteRows($tableName, $whereArray = null) {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			$sql = self::BuildSQLDelete($tableName, $whereArray);
			// Execute the UPDATE
			if (! $this->Query($sql)) {
				return false;
			} else {
				return true;
			}
		}
	}

	/**
	 * Returns true if the internal pointer is at the end of the records
	 *
	 * @return boolean TRUE if at the last row or FALSE if not
	 */
	public function EndOfSeek() {
		$this->ResetError();
		if ($this->IsConnected()) {
			if ($this->active_row >= ($this->RowCount())) {
				return true;
			} else {
				return false;
			}
		} else {
			$this->SetError("No connection");
			return false;
		}
	}

	/**
	 * Returns the last MySQL error as text
	 *
	 * @return string Error text from last known error
	 */
	public function Error() {
		$error = $this->error_desc;
		if (empty($error)) {
			if ($this->error_number <> 0) {
				$error = "Unknown Error (#" . $this->error_number . ")";
			} else {
				$error = false;
			}
		} else {
			if ($this->error_number > 0) {
				$error .= " (#" . $this->error_number . ")";
			}
		}
		return $error;
	}

	/**
	 * Returns the last MySQL error as a number
	 *
	 * @return integer Error number from last known error
	 */
	public function ErrorNumber() {
		if (strlen($this->error_desc) > 0)
		{
			if ($this->error_number <> 0)
			{
				return $this->error_number;
			} else {
				return -1;
			}
		} else {
			return $this->error_number;
		}
	}

	/**
	 * [STATIC] Converts any value of any datatype into boolean (true or false)
	 *
	 * @param mixed $value Value to analyze for TRUE or FALSE
	 * @return boolean Returns TRUE or FALSE
	 */
	static public function GetBooleanValue($value) {
		if (gettype($value) == "boolean") {
			if ($value == true) {
				return true;
			} else {
				return false;
			}
		} elseif (is_numeric($value)) {
			if ($value > 0) {
				return true;
			} else {
				return false;
			}
		} else {
			$cleaned = strtoupper(trim($value));

			if ($cleaned == "ON") {
				return true;
			} elseif ($cleaned == "SELECTED" || $cleaned == "CHECKED") {
				return true;
			} elseif ($cleaned == "YES" || $cleaned == "Y") {
				return true;
			} elseif ($cleaned == "TRUE" || $cleaned == "T") {
				return true;
			} else {
				return false;
			}
		}
	}

	/**
	 * Returns the comments for fields in a table into an
	 * array or NULL if the table has not got any fields
	 *
	 * @param string $table Table name
	 * @return array An array that contains the column comments
	 */
	public function GetColumnComments($table) {
		$this->ResetError();
		$records = mysql_query("SHOW FULL COLUMNS FROM " . $table);
		if (! $records) {
			$this->SetError();
			return false;
		} else {
			// Get the column names
			$columnNames = $this->GetColumnNames($table);
			if ($this->Error()) {
				return false;
			} else {
				$index = 0;
				// Fetchs the array to be returned (column 8 is field comment):
				while ($array_data = mysql_fetch_array($records)) {
					$columns[$index] = $array_data[8];
					$columns[$columnNames[$index++]] = $array_data[8];
				}
				return $columns;
			}
		}
	}

	/**
	 * This function returns the number of columns or returns FALSE on error
	 *
	 * @param string $table (Optional) If a table name is not specified, the
	 *                      column count is returned from the last query
	 * @return integer The total count of columns
	 */
	public function GetColumnCount($table = "") {
		$this->ResetError();
		if (empty($table)) {
			$result = mysql_num_fields($this->last_result);
			if (! $result) $this->SetError();
		} else {
			$records = mysql_query("SELECT * FROM " . $table . " LIMIT 1");
			if (! $records) {
				$this->SetError();
				$result = false;
			} else {
				$result = mysql_num_fields($records);
				$success = @mysql_free_result($records);
				if (! $success) {
					$this->SetError();
					$result = false;
				}
			}
		}
		return $result;
	}

	/**
	 * This function returns the data type for a specified column. If
	 * the column does not exists or no records exist, it returns FALSE
	 *
	 * @param string $column Column name or number (first column is 0)
	 * @param string $table (Optional) If a table name is not specified, the
	 *                      last returned records are used
	 * @return string MySQL data (field) type
	 */
	public function GetColumnDataType($column, $table = "") {
		$this->ResetError();
		if (empty($table)) {
			if ($this->RowCount() > 0) {
				if (is_numeric($column)) {
					return mysql_field_type($this->last_result, $column);
				} else {
					return mysql_field_type($this->last_result, $this->GetColumnID($column));
				}
			} else {
				return false;
			}
		} else {
			if (is_numeric($column)) $column = $this->GetColumnName($column, $table);
			$result = mysql_query("SELECT " . $column . " FROM " . $table . " LIMIT 1");
			if (mysql_num_fields($result) > 0) {
				return mysql_field_type($result, 0);
			} else {
				$this->SetError("The specified column or table does not exist, or no data was returned", -1);
				return false;
			}
		}
	}

	/**
	 * This function returns the position of a column
	 *
	 * @param string $column Column name
	 * @param string $table (Optional) If a table name is not specified, the
	 *                      last returned records are used.
	 * @return integer Column ID
	 */
	public function GetColumnID($column, $table = "") {
		$this->ResetError();
		$columnNames = $this->GetColumnNames($table);
		if (! $columnNames) {
			return false;
		} else {
			$index = 0;
			$found = false;
			foreach ($columnNames as $columnName) {
				if ($columnName == $column) {
					$found = true;
					break;
				}
				$index++;
			}
			if ($found) {
				return $index;
			} else {
				$this->SetError("Column name not found", -1);
				return false;
			}
		}
	}

   /**
	 * This function returns the field length or returns FALSE on error
	 *
	 * @param string $column Column name
	 * @param string $table (Optional) If a table name is not specified, the
	 *                      last returned records are used.
	 * @return integer Field length
	 */
	public function GetColumnLength($column, $table = "") {
		$this->ResetError();
		if (empty($table)) {
			if (is_numeric($column)) {
				$columnID = $column;
			} else {
				$columnID = $this->GetColumnID($column);
			}
			if (! $columnID) {
				return false;
			} else {
				$result = mysql_field_len($this->last_result, $columnID);
				if (! $result) {
					$this->SetError();
					return false;
				} else {
					return $result;
				}
			}
		} else {
			$records = mysql_query("SELECT " . $column . " FROM " . $table . " LIMIT 1");
			if (! $records) {
				$this->SetError();
				return false;
			}
			$result = mysql_field_len($records, 0);
			if (! $result) {
				$this->SetError();
				return false;
			} else {
				return $result;
			}
		}
	}

   /**
	 * This function returns the name for a specified column number. If
	 * the index does not exists or no records exist, it returns FALSE
	 *
	 * @param string $columnID Column position (0 is the first column)
	 * @param string $table (Optional) If a table name is not specified, the
	 *                      last returned records are used.
	 * @return integer Field Length
	 */
	public function GetColumnName($columnID, $table = "") {
		$this->ResetError();
		if (empty($table)) {
			if ($this->RowCount() > 0) {
				$result = mysql_field_name($this->last_result, $columnID);
				if (! $result) $this->SetError();
			} else {
				$result = false;
			}
		} else {
			$records = mysql_query("SELECT * FROM " . $table . " LIMIT 1");
			if (! $records) {
				$this->SetError();
				$result = false;
			} else {
				if (mysql_num_fields($records) > 0) {
					$result = mysql_field_name($records, $columnID);
					if (! $result) $this->SetError();
				} else {
					$result = false;
				}
			}
		}
		return $result;
	}

	/**
	 * Returns the field names in a table or query in an array
	 *
	 * @param string $table (Optional) If a table name is not specified, the
	 *                      last returned records are used
	 * @return array An array that contains the column names
	 */
	public function GetColumnNames($table = "") {
		$this->ResetError();
		if (empty($table)) {
			$columnCount = mysql_num_fields($this->last_result);
			if (! $columnCount) {
				$this->SetError();
				$columns = false;
			} else {
				for ($column = 0; $column < $columnCount; $column++) {
					$columns[] = mysql_field_name($this->last_result, $column);
				}
			}
		} else {
			$result = mysql_query("SHOW COLUMNS FROM " . $table);
			if (! $result) {
				$this->SetError();
				$columns = false;
			} else {
				while ($array_data = mysql_fetch_array($result)) {
					$columns[] = $array_data[0];
				}
			}
		}

		// Returns the array
		return $columns;
	}

	/**
	 * This function returns the last query as an HTML table
	 *
	 * @param boolean $showCount (Optional) TRUE if you want to show the row count,
	 *                           FALSE if you do not want to show the count
	 * @param string $styleTable (Optional) Style information for the table
	 * @param string $styleHeader (Optional) Style information for the header row
	 * @param string $styleData (Optional) Style information for the cells
	 * @return string HTML containing a table with all records listed
	 */
	public function GetHTML($showCount = true, $styleTable = null, $styleHeader = null, $styleData = null) {
		if ($styleTable === null) {
			$tb = "border-collapse:collapse;empty-cells:show";
		} else {
			$tb = $styleTable;
		}
		if ($styleHeader === null) {
			$th = "border-width:1px;border-style:solid;background-color:navy;color:white";
		} else {
			$th = $styleHeader;
		}
		if ($styleData === null) {
			$td = "border-width:1px;border-style:solid";
		} else {
			$td = $styleData;
		}

		if ($this->last_result) {
			if ($this->RowCount() > 0) {
				$html = "";
				if ($showCount) $html = "Record Count: " . $this->RowCount() . "<br />\n";
				$html .= "<table style=\"$tb\" cellpadding=\"2\" cellspacing=\"2\">\n";
				$this->MoveFirst();
				$header = false;
				while ($member = mysql_fetch_object($this->last_result)) {
					if (!$header) {
						$html .= "\t<tr>\n";
						foreach ($member as $key => $value) {
							$html .= "\t\t<td style=\"$th\"><strong>" . htmlspecialchars($key) . "</strong></td>\n";
						}
						$html .= "\t</tr>\n";
						$header = true;
					}
					$html .= "\t<tr>\n";
					foreach ($member as $key => $value) {
						$html .= "\t\t<td style=\"$td\">" . htmlspecialchars($value) . "</td>\n";
					}
					$html .= "\t</tr>\n";
				}
				$this->MoveFirst();
				$html .= "</table>";
			} else {
				$html = "No records were returned.";
			}
		} else {
			$this->active_row = -1;
			$html = false;
		}
		return $html;
	}

	/**
	 * Returns the last autonumber ID field from a previous INSERT query
	 *
	 * @return  integer ID number from previous INSERT query
	 */
	public function GetLastInsertID() {
		return $this->last_insert_id;
	}

	/**
	 * Returns the last SQL statement executed
	 *
	 * @return string Current SQL query string
	 */
	public function GetLastSQL() {
		return $this->last_sql;
	}

	/**
	 * This function returns table names from the database
	 * into an array. If the database does not contains
	 * any tables, the returned value is FALSE
	 *
	 * @return array An array that contains the table names
	 */
	public function GetTables() {
		$this->ResetError();
		// Query to get the tables in the current database:
		$records = mysql_query("SHOW TABLES");
		if (! $records) {
			$this->SetError();
			return FALSE;
		} else {
			while ($array_data = mysql_fetch_array($records)) {
				$tables[] = $array_data[0];
			}

			// Returns the array or NULL
			if (count($tables) > 0) {
				return $tables;
			} else {
				return FALSE;
			}
		}
	}

	/**
	 * Determines if a query contains any rows
	 *
	 * @param string $sql [Optional] If specified, the query is first executed
	 *                    Otherwise, the last query is used for comparison
	 * @return boolean TRUE if records exist, FALSE if not or query error
	 */
	public function HasRecords($sql = "") {
		if (strlen($sql) > 0) {
			$this->Query($sql);
			if ($this->Error()) return false;
		}
		if ($this->RowCount() > 0) {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * Inserts a row into a table in the connected database
	 *
	 * @param string $tableName The name of the table
	 * @param array $valuesArray An associative array containing the column
	 *                            names as keys and values as data. The values
	 *                            must be SQL ready (i.e. quotes around
	 *                            strings, formatted dates, ect)
	 * @return integer Returns last insert ID on success or FALSE on failure
	 */
	public function InsertRow($tableName, $valuesArray) {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			// Execute the query
			$sql = self::BuildSQLInsert($tableName, $valuesArray);
			if (! $this->Query($sql)) {
				return false;
			} else {
				return $this->GetLastInsertID();
			}
		}
	}

	/**
	 * Determines if a valid connection to the database exists
	 *
	 * @return boolean TRUE idf connectect or FALSE if not connected
	 */
	public function IsConnected() {
		if (gettype($this->mysql_link) == "resource") {
			return true;
		} else {
			return false;
		}
	}

	/**
	 * [STATIC] Determines if a value of any data type is a date PHP can convert
	 *
	 * @param date/string $value
	 * @return boolean Returns TRUE if value is date or FALSE if not date
	 */
	static public function IsDate($value) {
		$date = date('Y', strtotime($value));
		if ($date == "1969" || $date == '') {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Stop executing (die/exit) and show last MySQL error message
	 *
	 */
	public function Kill($message='') {
		if (strlen($message) > 0) {
			exit($message);
		} else {
			exit($this->Error());
		}
	}

	/**
	 * Seeks to the beginning of the records
	 *
	 * @return boolean Returns TRUE on success or FALSE on error
	 */
	public function MoveFirst() {
		$this->ResetError();
		if (! $this->Seek(0)) {
			$this->SetError();
			return false;
		} else {
			$this->active_row = 0;
			return true;
		}
	}

	/**
	 * Seeks to the end of the records
	 *
	 * @return boolean Returns TRUE on success or FALSE on error
	 */
	public function MoveLast() {
		$this->ResetError();
		$this->active_row = $this->RowCount() - 1;
		if (! $this->Error()) {
			if (! $this->Seek($this->active_row)) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	/**
	 * Connect to specified MySQL server
	 *
	 * @param string $database (Optional) Database name
	 * @param string $server   (Optional) Host address
	 * @param string $username (Optional) User name
	 * @param string $password (Optional) Password
	 * @param string $charset  (Optional) Character set
	 * @param boolean $pcon    (Optional) Persistant connection
	 * @return boolean Returns TRUE on success or FALSE on error
	 */
	public function Open($database="", $server="", $username="",
						 $password="", $charset="", $pcon=false) {
		$this->ResetError();

		// Use defaults?
		if (strlen($database) == 0) $database = $this->db_dbname;
		if (strlen($server)   == 0) $server   = $this->db_host;
		if (strlen($username) == 0) $username = $this->db_user;
		if (strlen($password) == 0) $password = $this->db_pass;
		if (strlen($charset)  == 0) $charset  = $this->db_charset;
		if (strlen($pcon)     == 0) $pcon     = $this->db_pcon;

		$this->active_row = -1;

		// Open persistent or normal connection
		if ($pcon) {
			$this->mysql_link = @mysql_pconnect($server, $username, $password);
		} else {
			$this->mysql_link = @mysql_connect ($server, $username, $password);
		}
		// Connect to mysql server failed?
		if (! $this->IsConnected()) {
			$this->SetError();
			return false;
		} else {
			// Select a database (if specified)
			if (strlen($database) > 0) {
				if (strlen($charset) == 0) {
					if (! $this->SelectDatabase($database)) {
						return false;
					} else {
						return true;
					}
				} else {
					if (! $this->SelectDatabase($database, $charset)) {
						return false;
					} else {
						return true;
					}
				}
			} else {
				return true;
			}
		}
	}

	/**
	 * Executes the given SQL query and returns the records
	 *
	 * @param string $sql The query string should not end with a semicolon
	 * @return object PHP 'mysql result' resource object containing the records
	 *                on SELECT, SHOW, DESCRIBE or EXPLAIN queries and returns;
	 *                TRUE or FALSE for all others i.e. UPDATE, DELETE, DROP
	 *                AND FALSE on all errors (setting the local Error message)
	 */
	public function Query($sql) {
		$this->ResetError();
		$this->last_sql = $sql;
		$this->last_result = @mysql_query($sql, $this->mysql_link);
		if(! $this->last_result) {
			$this->active_row = -1;
			$this->SetError();
			return false;
		} else {
			if (ereg("^insert", strtolower($sql))) {
				$this->last_insert_id = mysql_insert_id();
				if ($this->last_insert_id === false) {
					$this->SetError();
					return false;
				} else {
					$numrows = 0;
					$this->active_row = -1;
					return $this->last_result;
				}
			} else if(ereg("^select", strtolower($sql))) {
				$numrows = mysql_num_rows($this->last_result);
				if ($numrows > 0) {
					$this->active_row = 0;
				} else {
					$this->active_row = -1;
				}
				$this->last_insert_id = 0;
				return $this->last_result;
			} else {
				return $this->last_result;
			}
		}
	}

	/**
	 * Executes the given SQL query and returns a multi-dimensional array
	 *
	 * @param string $sql The query string should not end with a semicolon
	 * @param integer $resultType (Optional) The type of array
	 *                Values can be: MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH
	 * @return array A multi-dimensional array containing all the data
	 *               returned from the query or FALSE on all errors
	 */
	public function QueryArray($sql, $resultType = MYSQL_BOTH) {
		$this->Query($sql);
		if (! $this->Error()) {
			return $this->RecordsArray($resultType);
		} else {
			return false;
		}
	}

	/**
	 * Executes the given SQL query and returns only one (the first) row
	 *
	 * @param string $sql The query string should not end with a semicolon
	 * @return object PHP resource object containing the first row or
	 *                FALSE if no row is returned from the query
	 */
	public function QuerySingleRow($sql) {
		$this->Query($sql);
		if ($this->RowCount() > 0) {
			return $this->Row();
		} else {
			return false;
		}
	}

	/**
	 * Executes the given SQL query and returns the first row as an array
	 *
	 * @param string $sql The query string should not end with a semicolon
	 * @param integer $resultType (Optional) The type of array
	 *                Values can be: MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH
	 * @return array An array containing the first row or FALSE if no row
	 *               is returned from the query
	 */
	public function QuerySingleRowArray($sql, $resultType = MYSQL_BOTH) {
		$this->Query($sql);
		if ($this->RowCount() > 0) {
			return $this->RowArray(null, $resultType);
		} else {
			return false;
		}
	}

	/**
	 * Executa a query e returns um valor simples. Se mais que uma linha
	 * e obtida, somente o primeiro valor da primeira coluna da primeira linha e retornado
	 *
	 * @param string $sql A query string nao deve terminar com um ponto e virgula
	 * @return mixed O valor obtido ou FALSE se nao hover valor
	 */
	public function QuerySingleValue($sql) {
		$this->Query($sql);
		if ($this->RowCount() > 0 && $this->GetColumnCount() > 0) {
			$row = $this->RowArray(null, MYSQL_NUM);
			return $row[0];
		} else {
			return false;
		}
	}

	/**
	 * Executa a query SQL enviada, mede o tempo dessa açao, e salva a duraçao total
	 * em microsegundos
	 *
	 * @param string $sql A query string nao pode terminar com ponto e virgula
	 * @return object PHP 'mysql result' retorna objeto contendo os registros
	 *                de SELECT, SHOW, DESCRIBE ou EXPLAIN e retorna
	 *                TRUE ou FALSE para todos os outros ex. UPDATE, DELETE, DROP
	 */
	public function QueryTimed($sql) {
		$this->TimerStart();
		$result = $this->Query($sql);
		$this->TimerStop();
		return $result;
	}

	/**
	 * Retorna os registros da ultima query
	 *
	 * @return object PHP 'mysql result' objeto resultado contendo os registros
	 *                da ultima query executada
	 */
	public function Records() {
		return $this->last_result;
	}

	/**
	 * Retorna todos os registros da ultima query e retorna seu conteudo em uma matriz
	 * ou FALSE em caso de erro
	 *
	 * @param integer $resultType (Optional) O typo de matriz
	 *                Os valores podem ser: MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH
	 * @return Records na forma de matriz
	 */
	public function RecordsArray($resultType=MYSQL_BOTH) {
		$this->ResetError();
		if ($this->last_result) {
			if (! mysql_data_seek($this->last_result, 0)) {
				$this->SetError();
				return false;
			} else {
				//while($member = mysql_fetch_object($this->last_result)){
				while($member = mysql_fetch_array($this->last_result, $resultType)){
					$members[] = $member;
				}
				mysql_data_seek($this->last_result, 0);
				$this->active_row = 0;
				return $members;
			}
		} else {
			$this->active_row = -1;
			$this->SetError("Nao existem resultados para a query!", -1);
			return false;
		}
	}

	/**
	 * Libera a memoria usada para os resultados da query e retorna o resultado
	 *
	 * @return boolean Retorna TRUE successo ou FALSE se falhar
	 */
	public function Release() {
		$this->ResetError();
		if (! $this->last_result) {
			$success = true;
		} else {
			$success = @mysql_free_result($this->last_result);
			if (! $success) $this->SetError();
		}
		return $success;
	}

	/**
	 * Limpa as variaveis internas das informaçoes de erro
	 *
	 */
	private function ResetError() {
		$this->error_desc = '';
		$this->error_number = 0;
	}

	/**
	 * Le a linha atual e retorna o conteudo do resultado como
	 * um objeto PHP ou retorna FALSE error
	 *
	 * @param integer $optional_row_number (Optional) Use para especificar a linha desejada
	 * @return object objeto PHP ou FALSE error
	 */
	public function Row($optional_row_number = null) {
		$this->ResetError();
		if (! $this->last_result) {
			$this->SetError("Nao existem resultados!", -1);
			return false;
		} elseif ($optional_row_number === null) {
			if (($this->active_row) > $this->RowCount()) {
				$this->SetError("Nao posso ler apos o final dos registros!", -1);
				return false;
			} else {
				$this->active_row++;
			}
		} else {
			if ($optional_row_number >= $this->RowCount()) {
				$this->SetError("O numeor da linha e maior que o total de linhas!", -1);
				return false;
			} else {
				$this->active_row = $optional_row_number;
				$this->Seek($optional_row_number);
			}
		}
		$row = mysql_fetch_object($this->last_result);
		if (! $row) {
			$this->SetError();
			return false;
		} else {
			return $row;
		}
	}

	/**
	 * Le a linha atual e retorna seu conteudo como uma
	 * matriz ou retorna FALSE error
	 *
	 * @param integer $optional_row_number (Optional) Use para especificar uma linha
	 * @param integer $resultType (Optional) O tipo de matriz
	 *                Esses valores podem ser: MYSQL_ASSOC, MYSQL_NUM, MYSQL_BOTH
	 * @return array Matriz que correesponde a linha obtida ou FALSE se nçao ha linhas
	 */
	public function RowArray($optional_row_number = null, $resultType = MYSQL_BOTH) {
		$this->ResetError();
		if (! $this->last_result) {
			$this->SetError("Nao existem resultados!", -1);
			return false;
		} elseif ($optional_row_number === null) {
			if (($this->active_row) > $this->RowCount()) {
				$this->SetError("Nao posso ler apos o final dos registros!", -1);
				return false;
			} else {
				$this->active_row++;
			}
		} else {
			if ($optional_row_number >= $this->RowCount()) {
				$this->SetError("O numero de linha solicitado e maior que o total de linhas!", -1);
				return false;
			} else {
				$this->active_row = $optional_row_number;
				$this->Seek($optional_row_number);
			}
		}
		$row = mysql_fetch_array($this->last_result, $resultType);
		if (! $row) {
			$this->SetError();
			return false;
		} else {
			return $row;
		}
	}

	/**
	 * Retorna a contagem de linhas da ultima query
	 *
	 * @return integer numero de linhas ou FALSE error
	 */
	public function RowCount() {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("Nao ha coneccao!", -1);
			return false;
		} elseif (! $this->last_result) {
			$this->SetError("Nao existe nenhum resultado!", -1);
			return false;
		} else {
			$result = @mysql_num_rows($this->last_result);
			if (! $result) {
				$this->SetError();
				return false;
			} else {
				return $result;
			}
		}
	}

	/**
	 * Seta o ponteiro interno da base da dados para
	 * um numero de linha especifico e retorna o resultado
	 *
	 * @param integer $row_number Numero da linha
	 * @return object Linha obtida como objeto PHP
	 */
	public function Seek($row_number) {
		$this->ResetError();
		$row_count = $this->RowCount();
		if (! $row_count) {
			return false;
		} elseif ($row_number >= $row_count) {
			$this->SetError("Seek parameter is greater than the total number of rows", -1);
			return false;
		} else {
			$this->active_row = $row_number;
			$result = mysql_data_seek($this->last_result, $row_number);
			if (! $result) {
				$this->SetError();
				return false;
			} else {
				$record = mysql_fetch_row($this->last_result);
				if (! $record) {
					$this->SetError();
					return false;
				} else {
					// Go back to the record after grabbing it
					mysql_data_seek($this->last_result, $row_number);
					return $record;
				}
			}
		}
	}

	/**
	 * Retorna o cursos de localaziçao da linha atual
	 *
	 * @return integer Numero da linha atual
	 */
	public function SeekPosition() {
		return $this->active_row;
	}

	/**
	 * Seleciona uma base de dadso diferente da originalmente estabelecida e seu characterset
	 *
	 * @param string $database Nome da Base de dados
	 * @param string $charset (Optional) Character set (i.e. utf8)
	 * @return boolean Retorna TRUE successo ou FALSE error
	 */
	public function SelectDatabase($database, $charset = "") {
		$return_value = true;
		if (! $charset) $charset = $this->db_charset;
		$this->ResetError();
		if (! (mysql_select_db($database))) {
			$this->SetError();
			$return_value = false;
		} else {
			if ((strlen($charset) > 0)) {
				if (! (mysql_query("SET CHARACTER SET '{$charset}'", $this->mysql_link))) {
					$this->SetError();
					$return_value = false;
				}
			}
		}
		return $return_value;
	}

	/**
	 * Obtem as linhas da tabela que atendem a um filtro WHERE
	 *
	 * @param string $tableName O nome da tabela
	 * @param array $whereArray (Optional) An associative array containing the
	 *                          column names as keys and values as data. The
	 *                          values must be SQL ready (i.e. quotes around
	 *                          strings, formatted dates, ect)
	 * @param array/string $columns (Optional) The column or list of columns to select
	 * @param array/string $sortColumns (Optional) Column or list of columns to sort by
	 * @param boolean $sortAscending (Optional) TRUE for ascending; FALSE for descending
	 *                               This only works if $sortColumns are specified
	 * @param integer/string $limit (Optional) The limit of rows to return
	 * @return boolean Returns records on success or FALSE on error
	 */
	public function SelectRows($tableName, $whereArray = null, $columns = null,
							   $sortColumns = null, $sortAscending = true,
							   $limit = null) {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			$sql = self::BuildSQLSelect($tableName, $whereArray,
					$columns, $sortColumns, $sortAscending, $limit);
			// Execute the UPDATE
			if (! $this->Query($sql)) {
				return $this->last_result;
			} else {
				return true;
			}
		}
	}

	/**
	 * Retrorna todas a slinhas da tebela especificada
	 *
	 * @param string $tableName O nome da tabela
	 * @return boolean Returna os registro quando success ou  FALSE se error
	 */
	public function SelectTable($tableName) {
		return $this->SelectRows($tableName);
	}

	/**
	 * Seta as variaveis locais com a informaçao do ultimo erro
	 *
	 * @param string $errorMessage A descriçao do erro
	 * @param integer $errorNumber O numero do erro
       	 */
	private function SetError($errorMessage = '', $errorNumber = 0) {
		try {
			if (strlen($errorMessage) > 0) {
				$this->error_desc = $errorMessage;
			} else {
				if ($this->IsConnected()) {
					$this->error_desc = mysql_error($this->mysql_link);
				} else {
					$this->error_desc = mysql_error();
				}
			}
			if ($errorNumber <> 0) {
				$this->error_number = $errorNumber;
			} else {
				if ($this->IsConnected()) {
					$this->error_number = @mysql_errno($this->mysql_link);
				} else {
					$this->error_number = @mysql_errno();
				}
			}
		} catch(Exception $e) {
			$this->error_desc = $e->getMessage();
			$this->error_number = -999;
		}
		if ($this->ThrowExceptions) {
			throw new Exception($this->error_desc);
		}
	}

	/**
	 * [STATIC] Converte um boleano como valor formatado TRUE ou FALSE de sua opçao
	 *
	 * @param mixed $value Valor para analizar como TRUE ou FALSE
	 * @param mixed $trueValue Valor utilizado se TRUE
	 * @param mixed $falseValue Valor utilizado se FALSE
	 * @param string $datatype Use as constantes SQLVALUE ou as strings:
	 *                          string, text, varchar, char, boolean, bool,
	 *                          Y-N, T-F, bit, date, datetime, time, integer,
	 *                          int, number, double, float
	 * @return uma string SQL formatada com os tipos de dados especificados
	 */
	static public function SQLBooleanValue($value, $trueValue, $falseValue, $datatype = self::SQLVALUE_TEXT) {
		if (self::GetBooleanValue($value)) {
		   $return_value = self::SQLValue($trueValue, $datatype);
		} else {
		   $return_value = self::SQLValue($falseValue, $datatype);
		}
		return $return_value;
	}

	/**
	 * [STATIC] Retorna string ajustada para SQL
	 *
	 * @param string $value
	 * @return string valor formatado para o comando SQL
	 */
	static public function SQLFix($value) {
		return @addslashes($value);
	}

	/**
	 * [STATIC] Retorna uma string MySQL como uma string normal
	 *
	 * @param string $value
	 * @return string
	 */
	static public function SQLUnfix($value) {
		return @stripslashes($value);
	}

	/**
	 * [STATIC] Formata qualquer valor em uma string preparada para o comando SQL
	 * (NOTA: Tambem suporta tipos de dados retornados pela funçao gettype)
	 *
	 * @param mixed $value Qualeru valor de qualuer tipo para ser formatado para o comando SQL
	 * @param string $datatype Use constantes SQLVALUE ou as strings:
	 *                          string, text, varchar, char, boolean, bool,
	 *                          Y-N, T-F, bit, date, datetime, time, integer,
	 *                          int, number, double, float
	 * @return string
	 */
	static public function SQLValue($value, $datatype = self::SQLVALUE_TEXT) {
		$return_value = "";

		switch (strtolower(trim($datatype))) {
			case "text":
			case "string":
			case "varchar":
			case "char":
				if (strlen($value) == 0) {
					$return_value = "NULL";
				} else {
					$return_value = "'" . str_replace("'", "''", $value) . "'";
				}
				break;
			case "number":
			case "integer":
			case "int":
			case "double":
			case "float":
				if (is_numeric($value)) {
					$return_value = $value;
				} else {
					$return_value = "NULL";
				}
				break;
			case "boolean":  //boolean to use this with a bit field
			case "bool":
			case "bit":
				if (self::GetBooleanValue($value)) {
				   $return_value = "1";
				} else {
				   $return_value = "0";
				}
				break;
			case "y-n":  //boolean to use this with a char(1) field
				if (self::GetBooleanValue($value)) {
					$return_value = "'Y'";
				} else {
					$return_value = "'N'";
				}
				break;
			case "t-f":  //boolean to use this with a char(1) field
				if (self::GetBooleanValue($value)) {
					$return_value = "'T'";
				} else {
					$return_value = "'F'";
				}
				break;
			case "date":
				if (self::IsDate($value)) {
					$return_value = "'" . date('Y-m-d', strtotime($value)) . "'";
				} else {
					$return_value = "NULL";
				}
				break;
			case "datetime":
				if (self::IsDate($value)) {
					$return_value = "'" . date('Y-m-d H:i:s', strtotime($value)) . "'";
				} else {
					$return_value = "NULL";
				}
				break;
			case "time":
				if (self::IsDate($value)) {
					$return_value = "'" . date('H:i:s', strtotime($value)) . "'";
				} else {
					$return_value = "NULL";
				}
				break;
			default:
				exit("ERROR: Tipo de dado invalido especificado no metodo SQLValue!");
		}
		return $return_value;
	}

	/**
	 * Retorna a ultima mediçao de tempo medida (tempo entre TimerStart e TimerStop)
	 *
	 * @param integer $decimals (Optional) O numero de casas decimais mostradas
	 * @return Float Microsegundos decorridos
	 */
	public function TimerDuration($decimals = 4) {
		return number_format($this->time_diff, $decimals);
	}

	/**
	 * Inicia a mediçao de tempo (em microsegundos)
	 *
	 */
	public function TimerStart() {
		$parts = explode(" ", microtime());
		$this->time_diff = 0;
		$this->time_start = $parts[1].substr($parts[0],1);
	}

	/**
	 * Para a mediçao de tempo (em microsegundos)
	 *
	 */
	public function TimerStop() {
		$parts  = explode(" ", microtime());
		$time_stop = $parts[1].substr($parts[0],1);
		$this->time_diff  = ($time_stop - $this->time_start);
		$this->time_start = 0;
	}

	/**
	 * Inicia uma trasaçao
	 *
	 * @return boolean Returns TRUE successo ou FALSE error
	 */
	public function TransactionBegin() {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			if (! $this->in_transaction) {
				if (! mysql_query("START TRANSACTION", $this->mysql_link)) {
					$this->SetError();
					return false;
				} else {
					$this->in_transaction = true;
					return true;
				}
			} else {
				$this->SetError("Transaçao ja aberta!", -1);
				return false;
			}
		}
	}

	/**
	 * Finalisa a transaçao e salva todas as queries
	 *
	 * @return boolean Returns TRUE successo ou FALSE error
	 */
	public function TransactionEnd() {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			if ($this->in_transaction) {
				if (! mysql_query("COMMIT", $this->mysql_link)) {
					// $this->TransactionRollback();
					$this->SetError();
					return false;
				} else {
					$this->in_transaction = false;
					return true;
				}
			} else {
				$this->SetError("Nao e uma trasaçao!", -1);
				return false;
			}
		}
	}

	/**
	 * Desfaz da transaçao
	 *
	 * @return boolean Returns TRUE successo ou FALSE falha
	 */
	public function TransactionRollback() {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			if(! mysql_query("ROLLBACK", $this->mysql_link)) {
				$this->SetError("Nao posso desfazer a transaçao!");
				return false;
			} else {
				$this->in_transaction = false;
				return true;
			}
		}
	}

	/**
	 * Trunca a tabela removendo todos os dados
	 *
	 * @param string $tableName O nome da tabela
	 * @return boolean Returns TRUE successo ou FALSE error
	 */
	public function TruncateTable($tableName) {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			$sql = "TRUNCATE TABLE `" . $tableName . "`";
			if (! $this->Query($sql)) {
				return false;
			} else {
				return true;
			}
		}
	}

	/**
	 * Updates linhas em uma tabela baseado em um filtro WHERE
	 * (pode ser apenas uma ou muitas linhas baseadas no filtro)
	 *
	 * @param string $tableName O nome da tabela
	 * @param array $valuesArray Uma matriz associada contendo os nomes das colunas
	 *                            como chaves e valores como dados . Os valores devem
	 *                            estar prontos e formatados para o comamndo SQL
         *                            (ex. com aspas em trono de strings, data formatadas )
	 * @param array $whereArray (Opcional) Uma matriz associada contendo o nome das colunas
	 *                           com chaves e valores como dados. Os valores
         *                           devem estar prontos para o comando SQL. Se nao especificado
	 *                           entao todos os valores na tabela sao atualizados.
	 * @return boolean Returns TRUE successo ou FALSE error
	 */
	public function UpdateRows($tableName, $valuesArray, $whereArray = null) {
		$this->ResetError();
		if (! $this->IsConnected()) {
			$this->SetError("No connection");
			return false;
		} else {
			$sql = self::BuildSQLUpdate($tableName, $valuesArray, $whereArray);
			// Execute the UPDATE
			if (! $this->Query($sql)) {
				return false;
			} else {
				return true;
			}
		}
	}
}
?>
<?php
/*
 * Mysql Ajax Table Editor
 *
 * Copyright (c) 2008 Chris Kitchen <info@mysqlajaxtableeditor.com>
 * All rights reserved.
 *
 * See COPYING file for license information.
 *
 * Download the latest version from
 * http://www.mysqlajaxtableeditor.com
 */
class AjaxTableEditor
{
	var $action;
	var $retArr = array();
	var $warnings = array();
	var $html;
	var $numResults;
	var $inputIdPrefix;
	var $start;
	var $display;
	var $orderByColumn;
	var $ascOrDesc;
	var $searchString;
	var $selectClause;
	var $joinClause;
	var $whereClause;
	var $dbName;
	var $tableColumns = array();
	var $tableName;
	var $varPrefix;
	var $primaryKeyCol;
	var $userColumns = array();
	var $errorFun;
	var $permissions;
	var $tableTitle;
	var $valError;
	var $addRowTitle;
	var $editRowTitle;
	var $editMultTitle;
	var $viewRowTitle;
    var $showHideTitle;
    var $orderColTitle;
	var $searchType;
	var $numAdvSearches;
	var $opts;
	var $matchAll;
	var $advSearches = array();
	var $defNumAdvSearches;
	var $afterAddFun;
	var $afterEditFun;
	var $afterCopyFun;
	var $afterDeleteFun;
	var $extraOrderByInfo;
	var $information = array();
	var $userButtons = array();
	var $userIcons = array();
	var $tableInfo;
	var $oddRowColor;
	var $evenRowColor;
	var $userDefDisplay;
	var $displayNumInc;
	var $dbAndTable;
	var $maxDispNum;
	var $beginTime;
	var $viewQuery;
	var $recordInfo;
	var $replaceWithId;
	var $highlightHash;
	var $useHightlight;
	var $userActions = array();
	var $reqMarker;
	var $viewTableInfo;
	var $editTableInfo;
	var $addTableInfo;
	var $showHideTableInfo;
	var $addScreenFun;
	var $editScreenFun;
	var $viewScreenFun;
	var $tableScreenFun;
    var $showHideScreenFun;
    var $orderColScreenFun;
	var $valErrorStyles;
	var $noValErrorStyles;
	var $iconColPosition;
    var $mateUserId;
    var $pagePercent;
    var $pageRange;
    var $pageBegin;
    var $pageEnd;
    var $showAll;
    var $queryLineBreak;
    var $viewHtmlFun;
    var $handleHackingFun;
	var $removeIcons;
	var $langVars;
	var $iconTitle;
	var $sqlFilters;
	var $allowEditMult;
	var $defaultJsCalFormat;
	var $disableMultCbFun;
	var $extraRowInfo;
    
	function setDefaults()
	{
		$this->dbAndTable = !empty($this->dbName) ? $this->dbName.'.'.$this->tableName : $this->tableName;
		$this->warnings = array();
		$this->varPrefix = $this->primaryKeyCol.'-'.$this->tableName;
		$this->start = 0;
		$this->displayNum = isset($_SESSION[$this->varPrefix.'displayNum']) ? $_SESSION[$this->varPrefix.'displayNum'] : 20;
		$this->ascOrDesc = 'asc';
		$this->orderByColumn = $this->primaryKeyCol;
		$this->tableTitle = mb_convert_case(str_replace('_',' ',$this->tableName),MB_CASE_TITLE, "UTF-8");
		$this->valError = false;
		$this->addRowTitle = $this->langVars->ttlAddRow;
		$this->editRowTitle = $this->langVars->ttlEditRow;
		$this->editMultTitle = $this->langVars->ttlEditMult;
		$this->viewRowTitle = $this->langVars->ttlViewRow;
		$this->defNumAdvSearches = 3;
		$this->numAdvSearches = isset($_SESSION[$this->varPrefix.'numAdvSearches']) ? $_SESSION[$this->varPrefix.'numAdvSearches'] : $this->defNumAdvSearches;
		$this->opts = array('like' => $this->langVars->optLike, 'not like' => $this->langVars->optNotLike, '=' => $this->langVars->optEq, '<>' => $this->langVars->optNotEq, '>' => $this->langVars->optGreat, '<' => $this->langVars->optLess, '>=' => $this->langVars->optGreatEq, '<=' => $this->langVars->optLessEq);
		$this->searchType = isset($_SESSION[$this->varPrefix.'searchType']) ? $_SESSION[$this->varPrefix.'searchType'] : 'quick';
		$this->matchAll = isset($_SESSION[$this->varPrefix.'matchAll']) ? $_SESSION[$this->varPrefix.'matchAll'] : true;
		$this->advSearches = isset($_SESSION[$this->varPrefix.'advSearches']) ? $_SESSION[$this->varPrefix.'advSearches'] : array();
		$this->oddRowColor = '#FFFFFF';
		$this->evenRowColor = '#EDEDED';
		$this->userDefDisplay = false;
		$this->displayNumInc = 5;
		$this->maxDispNum = 200;
		$this->inputIdPrefix = '';
		$this->viewQuery = false;
		$this->recordInfo = true;
		$this->replaceWithId = '#primaryColValue#';
		$this->highlightHash = 'background-color: #FFF547';
		$this->useHighlight = true;
		$this->userActions = array();
		$this->reqMarker = '*';
		$this->viewTableInfo = 'class="mateTable"';
		$this->editTableInfo = 'class="mateTable"';
		$this->addTableInfo = 'class="mateTable"';
		$this->showHideTableInfo = 'class="mateTable"';
		$this->valErrorStyles = 'color: #f90d0d;';
		$this->noValErrorStyles = 'color: #333;';
		$this->iconColPosition = 'last';
		$this->showHideTitle = $this->langVars->ttlShowHide;
		$this->orderColTitle = $this->langVars->ttlOrderCols;
		$this->pagePercent = 20;
		$this->pageRange = 10;
		$this->pageBegin = 5;
		$this->pageEnd = 5;
		$this->showAll = 200;
		$this->queryLineBreak = "\n";
		$this->allowEditMult = true;
		$this->defaultJsCalFormat = '%d %B %Y';
	}
	
	function setConfig($var,$val)
	{
		if($var == 'varPrefix')
		{
			// If the variable prefix changed, update variables with session values
			$varPrefix = $val;
			foreach($_SESSION as $sessKey => $sessValue)
			{
				if(substr($sessKey,0,strlen($varPrefix)) == $varPrefix)
				{
					$varName = str_replace($varPrefix,'',$sessKey);
					$this->{$varName} = $sessValue;
				}
			}
		}
		if(!isset($_SESSION[$this->varPrefix.$var]))
		{
			$this->{$var} = $val; 
		}
	}
	
	function doDefault()
	{
		if(isset($this->userActions[$this->action]) && is_callable($this->userActions[$this->action]))
		{
			call_user_func($this->userActions[$this->action],$this->info);
		}
		else
		{
			$this->warnings[] = sprintf($this->langVars->errNoAction,$this->action);
		}
	}
	
	function displayInformation()
	{
		if(!empty($this->information))
		{
			$this->retArr[] = array('layer_id' => 'information', 'where' => 'innerHTML', 'value' => implode('<br /><br />',$this->information));	
		}	
	}	
	function displayWarnings()
	{
		if(!empty($this->warnings))
		{
			$this->retArr[] = array('where' => 'javascript', 'value' => 'alert(\''.implode('\n',$this->warnings).'\');');	
		}	
	}		
	
	function doQuery($query)
	{
		$result = mysql_query($query);
		if (!$result)
		{
			$message = '<br /><br  />'.$this->langVars->errQuery.' <br />'.$query.'<br /><br /> '.$this->langVars->errMysql.'<br /> '.mysql_error();
			call_user_func($this->errorFun,$message,__FILE__,__LINE__);
		}
		return $result;					
	}
	
	function escapeData($data) 
	{
		if (ini_get('magic_quotes_gpc')) 
		{
			$data = stripslashes($data);
		}
		if(isset($this->dbc))
		{
			return mysql_real_escape_string (trim ($data), $this->dbc);
		}
		else
		{
			return mysql_real_escape_string (trim ($data));
		}			
	}
	
	function startTimer()
	{
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$this->beginTime = $time;
	}
	
	function endTimer()
	{
		$time = microtime();
		$time = explode(' ', $time);
		$time = $time[1] + $time[0];
		$endtime = $time;
		$totaltime = ($endtime - $this->beginTime);
		$this->information[] = $totaltime;
	}
	
	function main($action,$info = '')
	{
		//$this->startSession();
		//$this->mysqlConnect();
		//$this->checkLoginInfo(array('Admin'));
		//$this->startTimer();
		$this->action = $action;
		$this->info = $info;
		$this->handleFlow();
		//$this->endTimer();
		$this->displayInformation();
		$this->displayWarnings();
	}
	
	function handleFlow()
	{
		switch ($this->action)
		{
			case 'update_mult_rows':
				$this->updateMultRows();
				if(!$this->valError)
				{
					$this->updateHtml();
				}
				break;
			case 'edit_mult_rows':
				$this->editMultRows();
				break;
			case 'reset_column_order':
				$this->resetColumnOrder();
				break;
			case 'update_column_order':
				$this->updateColumnOrder();
				break;
			case 'order_columns_screen':
				$this->orderColumnsScreen();
				break;
            case 'show_column':
				$this->showColumn();
				break;
            case 'hide_column':
				$this->hideColumn();
				break;
            case 'show_hide_columns':
				$this->showHideColumns();
				break;
			case 'view_row':
				$this->viewRow();
				break;
			case 'update_row':
				$this->updateRow();
				if(!$this->valError)
				{
					$this->updateHtml();
				}
				break;
			case 'cancel_edit_row':
				$this->updateHtml();
				break;
			case 'edit_row':
				$this->editRow();
				break;
			case 'delete_row':
				$this->deleteRow();
				break;
			case 'delete_mult_rows':
				$this->deleteMultRows();
				break;
			case 'update_html':
				$this->updateHtml();
				break;
			case 'handle_search':
				$_SESSION[$this->varPrefix.'searchType'] = 'quick';
				$this->searchType = 'quick';
				$_SESSION[$this->varPrefix.'ss'] = $this->info;
				$_SESSION[$this->varPrefix.'start'] = 0;				
				$this->updateHtml();
				//$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
				break;
			case 'page_num_changed':
				$_SESSION[$this->varPrefix.'start'] = $this->escapeData($this->info);
				$this->updateHtml();
				break;
			case 'display_num_changed':
				$this->displayNum = $this->escapeData($this->info);
				$_SESSION[$this->varPrefix.'displayNum'] = $this->displayNum;
				$_SESSION[$this->varPrefix.'start'] = 0;
				$this->updateHtml();
				$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
				break;
			case 'order_by_changed':
				$_SESSION[$this->varPrefix.'obc'] = $this->escapeData($this->info[0]);
				$_SESSION[$this->varPrefix.'aod'] = $this->escapeData($this->info[1]);
				$this->displayTable();
				break;
			case 'add_row':
				$this->addRow();
				break;
			case 'insert_row':
				$this->insertRow();
				if(!$this->valError)
				{
					$this->updateHtml();
				}
				break;
			case 'show_advanced_search':
				$_SESSION[$this->varPrefix.'numAdvSearches'] = $this->defNumAdvSearches;
				$this->numAdvSearches = $this->defNumAdvSearches;
				$_SESSION[$this->varPrefix.'ss'] = '';
				$this->searchString = '';
				$_SESSION[$this->varPrefix.'searchType'] = 'advanced';
				$this->searchType = 'advanced';
				$this->updateHtml();
				break;
			case 'show_quick_search':
				$_SESSION[$this->varPrefix.'advSearches'] = array();
				$this->advSearches = array();
				$_SESSION[$this->varPrefix.'searchType'] = 'quick';
				$this->searchType = 'quick';
				$this->updateHtml();
				break;
			case 'advanced_search':
				$_SESSION[$this->varPrefix.'searchType'] = 'advanced';
				$this->searchType = 'advanced';
				$this->advancedSearch();
				$this->updateHtml();
				//$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
				break;	
			case 'match_all':
				$_SESSION[$this->varPrefix.'matchAll'] = true;
				break;
			case 'match_any':
				$_SESSION[$this->varPrefix.'matchAll'] = false;
				break;
			case 'clear_adv_search':
				$_SESSION[$this->varPrefix.'matchAll'] = true;
				$this->matchAll = true;
				$_SESSION[$this->varPrefix.'numAdvSearches'] = $this->defNumAdvSearches;
				$this->numAdvSearches = $this->defNumAdvSearches;
				$_SESSION[$this->varPrefix.'advSearches'] = array();
				$this->advSearches = array();
				$this->updateHtml();
				break;
			case 'add_adv_search':
				$_SESSION[$this->varPrefix.'numAdvSearches']++;
				$this->numAdvSearches++;
				$this->updateHtml();
				break;
			case 'copy_mult_rows':
				$this->copyMultRows();
				$this->updateHtml();
				break;
			case 'copy_row':
				$this->copyRow();
				$this->updateHtml();
				break;
			case 'user_icon_clicked':
				$this->userIconClicked();
				break;
			case 'user_button_clicked':
				$this->userButtonClicked();
				break;
			default :
				$this->doDefault();
		}
	}
    
	function resetColumnOrder()
	{
		$query = "update mate_columns set order_num = '0' where mate_var_prefix = '".$this->escapeData($this->varPrefix)."'";
		$result = $this->doQuery($query);
		if($result)
		{
			$this->orderColumnsScreen();
		}
	}
	
    function unsetColSessVars($column)
    {
        if($this->searchType == 'advanced')
        {
            foreach($_SESSION[$this->varPrefix.'advSearches'] as $key => $searchInfo)
            {
                if(isset($searchInfo['cols']) && $searchInfo['cols'] == $column)
                {
                    $_SESSION[$this->varPrefix.'advSearches'][$key] = array();
                }
            }
        }
        if(isset($_SESSION[$this->varPrefix.'obc']) && $_SESSION[$this->varPrefix.'obc'] == $column)
        {
            unset($_SESSION[$this->varPrefix.'obc']);
        }
    }
    
    function unsetHiddenColumns()
    {
        if(stristr($this->permissions,'H') && $this->setMateUserId())
		{
            foreach($this->tableColumns as $col => $info)
            {
                if(stristr($info['perms'],'H') && $col != $this->primaryKeyCol)
                {
                    $hidden = isset($info['hidden']) ? $info['hidden'] : false;
                    if($this->columnIsHidden($col,$hidden))
                    {
                        unset($this->tableColumns[$col]);
                    }
                }
            }
        }
    }
    
    function columnIsHidden($column,$hidden = false)
    {
        $rv = false;
        $query = "select id, hidden from mate_columns where mate_user_id = '$this->mateUserId' and mate_var_prefix = '$this->varPrefix' and mate_column = '$column'";
        $result = $this->doQuery($query);
        if($row = mysql_fetch_assoc($result))
        {
            if($row['hidden'] == 'Yes')
            {
                $rv = true;
            }
        }
        else if($hidden)
        {
            $rv = true;
        }
        return $rv;
    }
    
    function showColumn()
    {
        if($this->setMateUserId())
        {
            $column = $this->escapeData($this->info);
            $query = "select id from mate_columns where mate_user_id = '$this->mateUserId' and mate_var_prefix = '$this->varPrefix' and mate_column = '$column'";
            $result = $this->doQuery($query);
            if($row = mysql_fetch_assoc($result))
            {
                $mateColId = $row['id'];
                $query = "update mate_columns set hidden = 'No' where id = '$mateColId'";
                $result = $this->doQuery($query);
            }
            else
            {
                $query = "insert into mate_columns set mate_user_id = '$this->mateUserId', mate_var_prefix = '$this->varPrefix', mate_column = '$column', hidden = 'No', date_updated = NOW()";
                $result = $this->doQuery($query);
            }
        }
    }
    
    function hideColumn()
    {
        if($this->setMateUserId())
        {
            $column = $this->escapeData($this->info);
            if(isset($this->tableColumns[$column]['perms']) && stristr($this->tableColumns[$column]['perms'],'H'))
            {
                $query = "select id from mate_columns where mate_user_id = '$this->mateUserId' and mate_var_prefix = '$this->varPrefix' and mate_column = '$column'";
                $result = $this->doQuery($query);
                if($row = mysql_fetch_assoc($result))
                {
                    $mateColId = $row['id'];
                    $query = "update mate_columns set hidden = 'Yes' where id = '$mateColId'";
                    $result = $this->doQuery($query);
                }
                else
                {
                    $query = "insert into mate_columns set mate_user_id = '$this->mateUserId', mate_var_prefix = '$this->varPrefix', mate_column = '$column', hidden = 'Yes', date_updated = NOW()";
                    $result = $this->doQuery($query);
                }
                $this->unsetColSessVars($column);
            }
        }
    }
    
    function setMateUserId()
    {
        if(isset($_COOKIE['mate_user_id']) && strlen($_COOKIE['mate_user_id']) > 0)
        {
            $this->mateUserId = $_COOKIE['mate_user_id'];
            return true;
        }
        else
        {
            $this->mateUserId = md5(uniqid(rand(),1));
            if(setcookie('mate_user_id',$this->mateUserId,time() + 60*60*24*7*365*2))
            {
                return true;
            }
            else
            {
                $this->warnings[] = 'Your browser must accept cookies in order to show/hide columns.';
                return false;
            }
        }
    }
    
    function showHideColumns()
    {
		if(stristr($this->permissions,'H'))
		{
            if($this->setMateUserId())
            {
                $html = '<table '.$this->showHideTableInfo.'><tr style="font-weight: bold;"><td>'.$this->langVars->ttlColumn.'</td><td>'.$this->langVars->ttlCheckBox.'</td></tr>';
                foreach($this->tableColumns as $col => $info)
                {
                    if(stristr($info['perms'],'H') && $col != $this->primaryKeyCol)
                    {
                        $checked = 'checked="checked"';
                        $hidden = isset($info['hidden']) ? $info['hidden'] : false;
                        if($this->columnIsHidden($col,$hidden))
                        {
                            $checked = '';
                        }
                        $html .= '<tr><td><label for="'.$col.'_hide_cb">'.$info['display_text'].'</label>:</td><td><input type="checkbox" id="'.$col.'_hide_cb" onchange="showHideColumn(this,\''.$col.'\');" '.$checked.' /></tr>';
                    }
                }
                $html .= '</table>';
                $this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->showHideTitle);
                $this->retArr[] = array('layer_id' => 'recordLayer', 'where' => 'innerHTML', 'value' => '');		
                $this->retArr[] = array('layer_id' => 'searchButtonsLayer', 'where' => 'innerHTML', 'value' => '<button onclick="toAjaxTableEditor(\'update_html\',\'\');">'.$this->langVars->btnBack.'</button>');		
                $this->retArr[] = array('layer_id' => 'tableLayer', 'where' => 'innerHTML', 'value' => $html);
                //$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
                if(is_callable($this->showHideScreenFun))
                {
                    call_user_func($this->showHideScreenFun);
                }
            }
        }
    }
    
    function orderColumnsScreen()
    {
		if(stristr($this->permissions,'O'))
		{
            $html = '';
            $this->unsetHiddenColumns();
            $this->setColumnOrder();
            if($this->setMateUserId())
            {
                $html .= '<ul id="columnOrderList">';
                foreach($this->tableColumns as $col => $info)
                {
                    if(stristr($info['perms'],'O'))
                    {
                        $html .= '<li id="columnOrderList_'.str_replace('_','-.-UNDERSCORE-.-',$col).'" class="orderColumnClass">'.$info['display_text'].'</li>';
                    }
                }
                $html .= '</ul>';
                $this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->orderColTitle);
                $this->retArr[] = array('layer_id' => 'recordLayer', 'where' => 'innerHTML', 'value' => '');		
                $this->retArr[] = array('layer_id' => 'searchButtonsLayer', 'where' => 'innerHTML', 'value' => '<button onclick="toAjaxTableEditor(\'reset_column_order\',\'\');">'.$this->langVars->btnReset.'</button> <button onclick="toAjaxTableEditor(\'update_html\',\'\');">'.$this->langVars->btnBack.'</button>');		
                $this->retArr[] = array('layer_id' => 'tableLayer', 'where' => 'innerHTML', 'value' => $html);
                //$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
                $javascript = 'Sortable.create("columnOrderList",{dropOnEmpty:false,constraint:false,onUpdate:function(element){var info = Sortable.serialize(element.id); toAjaxTableEditor("update_column_order",info); } });';
                $this->retArr[] = array('where' => 'javascript', 'value' => $javascript);
                if(is_callable($this->orderColScreenFun))
                {
                    call_user_func($this->orderColScreenFun);
                }
            }
        }
    }
    
    function updateColumnOrder()
    {
        if($this->setMateUserId())
        {
            if(stristr($this->permissions,'O'))
            {
                parse_str($this->info);
                $orderNum = 1;
                foreach($columnOrderList as $col)
                {
                    $col = str_replace('-.-UNDERSCORE-.-','_',$col);
                    if(isset($this->tableColumns[$col]['perms']) && stristr($this->tableColumns[$col]['perms'],'O'))
                    {
                        $query = "select id from mate_columns where mate_user_id = '$this->mateUserId' and mate_var_prefix = '$this->varPrefix' and mate_column = '$col'";
                        $result = $this->doQuery($query);
                        if($row = mysql_fetch_assoc($result))
                        {
                            $mateColId = $row['id'];
                            $query = "update mate_columns set order_num = '$orderNum' where id = '$mateColId'";
                            $result = $this->doQuery($query);
                        }
                        else
                        {
                            $query = "insert into mate_columns set mate_user_id = '$this->mateUserId', mate_var_prefix = '$this->varPrefix', mate_column = '$col', order_num = '$orderNum', hidden = 'No', date_updated = NOW()";
                            $result = $this->doQuery($query);
                        }
                        $orderNum++;
                    }
                }
            }
        }
    }
    
    function setColumnOrder()
    {
		if(stristr($this->permissions,'O'))
		{
            if($this->setMateUserId())
            {
                $newTableColumns = array();
                $query = "select mate_column from mate_columns where mate_user_id = '$this->mateUserId' and mate_var_prefix = '$this->varPrefix' and hidden = 'No' and order_num > 0 order by order_num asc";
                $result = $this->doQuery($query);
                while($row = mysql_fetch_assoc($result))
                {
                    if(isset($this->tableColumns[$row['mate_column']]) && stristr($this->tableColumns[$row['mate_column']]['perms'],'O'))
                    {
                        $newTableColumns[$row['mate_column']] = $this->tableColumns[$row['mate_column']];
                        unset($this->tableColumns[$row['mate_column']]);
                    }
                }
                foreach($this->tableColumns as $col => $info)
                {
                    $newTableColumns[$col] = $info;
                }
                $this->tableColumns = $newTableColumns;
            }
        }
    }
    
	function userButtonClicked()
	{
		$buttonKey = $this->info->buttonKey;
		if(isset($this->userButtons[$buttonKey]['pass_id_array']) && $this->userButtons[$buttonKey]['pass_id_array'])
		{
			$params = isset($this->userButtons[$buttonKey]['params']) ? $this->userButtons[$buttonKey]['params'] : array();
			call_user_func($this->userButtons[$buttonKey]['call_back_fun'],get_object_vars($this->info->checkboxes),$params);
		}
		else
		{
			foreach($this->info->checkboxes as $info)
			{
				$id = $this->escapeData($info);
				$query = "select * from $this->tableName where $this->primaryKeyCol = '$id'";
				$result = $this->doQuery($query);
				if($row = mysql_fetch_assoc($result))
				{
					if(isset($this->userButtons[$buttonKey]['call_back_fun']) && is_callable($this->userButtons[$buttonKey]['call_back_fun']))
					{
						$params = isset($this->userButtons[$buttonKey]['params']) ? $this->userButtons[$buttonKey]['params'] : array();
						call_user_func($this->userButtons[$buttonKey]['call_back_fun'],$row,$params);
					}
				}		
			}
		}
		if(!(isset($this->userButtons[$buttonKey]['no_update']) && $this->userButtons[$buttonKey]['no_update']))
		{
			$this->updateHtml();
		}
	}

	function userIconClicked()
	{
		$id = $this->escapeData($this->info[0]);
		$iconKey = $this->escapeData($this->info[1]);
		$query = "select * from $this->tableName where $this->primaryKeyCol = '$id'";
		$result = $this->doQuery($query);
		if($row = mysql_fetch_assoc($result))
		{
			if(isset($this->userIcons[$iconKey]['call_back_fun']) && is_callable($this->userIcons[$iconKey]['call_back_fun']))
			{
				call_user_func($this->userIcons[$iconKey]['call_back_fun'],$row);
			}
		}
		if(!(isset($this->userIcons[$iconKey]['no_update']) && $this->userIcons[$iconKey]['no_update']))
		{
			$this->updateHtml();
		}
	}

	function copyMultRows()
	{
		foreach($this->info as $id)
		{
			$afterCopyArray = array();
			$id = $this->escapeData($id);
			if($this->hasRightsToRow($id))
            {
                $query = "select * from $this->tableName where $this->primaryKeyCol = '$id'";
                $result = $this->doQuery($query);
                if($row = mysql_fetch_assoc($result))
                {
                    $sets = array();
                    foreach($this->tableColumns as $col => $info)
                    {
                        if(stristr($info['perms'],'C') && !isset($info['join']['real_column']))
                        {
                            $val = $row[$col];
                            if(isset($this->tableColumns[$col]['on_copy_fun']) && is_callable($this->tableColumns[$col]['on_copy_fun']))
                            {
                                $val = call_user_func($this->tableColumns[$col]['on_copy_fun'],$col,$val,$row);
                            }
                            $val = $this->escapeData($val);
                            $sets[] = $this->addTickMarks($col)." = '".$val."'";
                            $afterCopyArray[$col] = $val;
                        }
                    }
                    $query2 = "insert into $this->tableName set ".implode(', ',$sets);
                    $result2 = $this->doQuery($query2);
                    if($result2 && isset($this->afterCopyFun) && is_callable($this->afterCopyFun))
                    {
                        call_user_func($this->afterCopyFun,mysql_insert_id(),$afterCopyArray);
                    }
                }
            }
            else
            {
                $this->handleHacking();
            }
		}
	}

	function copyRow()
	{
        $afterCopyArray = array();
		$id = $this->escapeData($this->info);
        if($this->hasRightsToRow($id))
        {
            $query = "select * from $this->tableName where $this->primaryKeyCol = '$id'";
            $result = $this->doQuery($query);
            if($row = mysql_fetch_assoc($result))
            {
                $sets = array();
                foreach($this->tableColumns as $col => $info)
                {
                    if(stristr($info['perms'],'C') && !isset($info['join']['real_column']))
                    {
                        $val = $row[$col];
                        if(isset($this->tableColumns[$col]['on_copy_fun']) && is_callable($this->tableColumns[$col]['on_copy_fun']))
                        {
                            $val = call_user_func($this->tableColumns[$col]['on_copy_fun'],$col,$val,$row);
                        }
                        $val = $this->escapeData($val);
                        $sets[] = $this->addTickMarks($col)." = '".$val."'";
                        $afterCopyArray[$col] = $val;
                    }
                }
                $query2 = "insert into $this->tableName set ".implode(', ',$sets);
                $result2 = $this->doQuery($query2);
                if($result2 && isset($this->afterCopyFun) && is_callable($this->afterCopyFun))
                {
                    call_user_func($this->afterCopyFun,mysql_insert_id(),$afterCopyArray);
                }
            }
        }
        else
        {
            $this->handleHacking();
        }
	}
	
	function advancedSearch()
	{
		$_SESSION[$this->varPrefix.'start'] = 0;
		$this->info = get_object_vars($this->info);
		foreach($this->info as $i => $info)
		{
			$this->advSearches[$i] = get_object_vars($this->info[$i]);
		}
		$_SESSION[$this->varPrefix.'advSearches'] = $this->advSearches;
	}
	
	function exportInfo()
	{
		$this->unsetHiddenColumns();
		$this->setColumnOrder();
		$this->setPagingVars();
		$this->formatJoinClause();
		$this->formatSelectClause();
		$this->formatWhereClause();
		$this->setNumResults();
		$csvInfo = '';
		$csvLb = "\r\n";
		$this->extraOrderByInfo = empty($this->extraOrderByInfo) ? '' : ', '.$this->extraOrderByInfo;
		$query = $this->selectClause.' '.$this->joinClause.' '.$this->whereClause.' order by '.$this->addTickMarks($this->orderByColumn).' '.$this->getAscOrDesc().' '.$this->extraOrderByInfo;
		$result = $this->doQuery($query);
		if(mysql_num_rows($result) > 0)
		{
			$csvRow = array();
			foreach($this->tableColumns as $col => $info)
			{
				if(stristr($info['perms'],'X'))
				{
					$csvRow[] = '"'.$info['display_text'].'"';
				}
			}
			$csvInfo .= implode(',', $csvRow).$csvLb;
			
			while($row = mysql_fetch_assoc($result))
			{
				$csvRow = array();
				foreach($this->tableColumns as $col => $info)
				{
					$value = '';
					if(stristr($info['perms'],'X'))
					{
						$value = $row[$col];
						if(isset($info['csv_export_fun']) && is_callable($info['csv_export_fun']))
						{
							$value = call_user_func($info['csv_export_fun'],$col,$value,$row);
						}
						$csvRow[] = '"'.str_replace(array("\r", "\n", '"'),array('','','""'),$value).'"';
					}
				}
				$csvInfo .= implode(',', $csvRow).$csvLb;
			}
			return $csvInfo;
		}
	}
	
	function formatIcons($id,$row)
	{
		$html = '';
		$numIcons = 0;						
		if(stristr($this->permissions,'V') && stristr($this->removeIcons,'V') === false)
		{
			$html .= '<li class="info"><a href="javascript: toAjaxTableEditor(\'view_row\',\''.$id.'\');" title="'.$this->langVars->ttlInfo.'"></a></li>';
			$numIcons++;
		}
		if(stristr($this->permissions,'E') && stristr($this->removeIcons,'E') === false)
		{
			$html .= '<li class="edit"><a href="javascript: toAjaxTableEditor(\'edit_row\',\''.$id.'\');" title="'.$this->langVars->ttlEdit.'"></a></li>';
			$numIcons++;
		}
		if(stristr($this->permissions,'C') && stristr($this->removeIcons,'C') === false)
		{
			$html .= '<li class="copy"><a href="javascript: toAjaxTableEditor(\'copy_row\',\''.$id.'\');" title="'.$this->langVars->ttlCopy.'"></a></li>';
			$numIcons++;
		}
		if(stristr($this->permissions,'D') && stristr($this->removeIcons,'D') === false)
		{
			$html .= '<li class="delete"><a href="javascript: confirmDeleteRow(\''.$id.'\')" title="'.$this->langVars->ttlDelete.'"></a></li>';
			$numIcons++;
		}
		foreach($this->userIcons as $iconKey => $info)
		{
			if(isset($info['call_back_fun']) && is_callable($info['call_back_fun']))
			{
				$confirmMsg = isset($info['confirm_msg']) ? $info['confirm_msg'] : '';
				$html .= '<li class="'.$info['class'].'"><a href="javascript: userIconClicked(\'user_icon_clicked\',new Array(\''.$id.'\',\''.$iconKey.'\'),\''.$confirmMsg.'\')" title="'.$info['title'].'"></a></li>';
				$numIcons++;
			}
			else if(isset($info['icon_html']))
			{
				$info['icon_html'] = str_replace($this->replaceWithId,$id,$info['icon_html']);
				$html .= $info['icon_html'];
				$numIcons++;
			}
			else if(isset($info['format_fun']) && is_callable($info['format_fun']))
			{
				$userIconInfo = call_user_func($info['format_fun'],$row);
				$html .= $userIconInfo['icon_html'];
				$numIcons = $numIcons + $userIconInfo['num_icons'];				
			}
		}				
		if($numIcons > 0)
		{
			$width = $numIcons * 26;
			$html = '<td nowrap="nowrap"><ul class="actions" style="width: '.$width.'px;">'.$html.'</ul></td>';
		}
		
		return $html;
	}
	
	function viewRow()
	{
		if(stristr($this->permissions,'V'))
		{
			$html = '';
			$id = $this->escapeData($this->info);
			$this->formatJoinClause();
			$this->formatSelectClause();
			$query = $this->selectClause.' '.$this->joinClause.' where '.$this->tableName.'.'.$this->primaryKeyCol." = '$id'";
			$result = $this->doQuery($query);
			if($row = mysql_fetch_assoc($result))
			{
				$html .= '<table '.$this->viewTableInfo.'>';
				foreach($this->tableColumns as $col => $info)
				{
					if(stristr($info['perms'],'V'))
					{
						$value = $row[$col];
						if(isset($info['view_fun']) && is_callable($info['view_fun']))
						{
							$value = call_user_func($info['view_fun'],$col,$value,$row);
						}
						$value = strlen(trim($value)) > 0 ? $value : '&nbsp;';
						$html .= '<tr><td id="'.$col.'_label_cell" class="labelCell">'.$info['display_text'].':</td><td id="'.$col.'_value_cell" class="valueCell">'.$value.'</td></tr>';
					}
				}
                if(isset($this->viewHtmlFun) && is_callable($this->viewHtmlFun))
                {
                    $html .= call_user_func($this->viewHtmlFun,$row);
                }
				$html .= '</tr></table><div id="viewRowButtons">';
				if(stristr($this->permissions,'E'))
				{
					$html .= '<button class="ajaxButton" onclick="toAjaxTableEditor(\'edit_row\',\''.$id.'\');">'.$this->langVars->btnEdit.'</button>';
				}
				$html .= '<button class="ajaxButton" onclick="toAjaxTableEditor(\'update_html\',\'\');">'.$this->langVars->btnBack.'</button></div>';
			}
			$this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->viewRowTitle);		
			$this->retArr[] = array('layer_id' => 'recordLayer', 'where' => 'innerHTML', 'value' => '');		
			$this->retArr[] = array('layer_id' => 'searchButtonsLayer', 'where' => 'innerHTML', 'value' => '');		
			$this->retArr[] = array('layer_id' => 'tableLayer', 'where' => 'innerHTML', 'value' => $html);
			//$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
			
			if(is_callable($this->viewScreenFun))
			{
				call_user_func($this->viewScreenFun);
			}
		}		
	}

	function updateMultRows()
	{
		$this->valError = false;
		$sets = array();
		$afterEditArray = array();
		$idArr = $this->info->idArr;
		$inputInfo = get_object_vars($this->info->inputInfo);

		foreach($inputInfo as $col => $val)
		{
			$col = str_replace($this->inputIdPrefix,'',$col);
			if(isset($this->tableColumns[$col]))
			{
				if(isset($this->tableColumns[$col]['on_edit_fun']) && is_callable($this->tableColumns[$col]['on_edit_fun']))
				{
					$val = call_user_func($this->tableColumns[$col]['on_edit_fun'],$col,$val,$this->info);
				}
				// Check Validation
				if(isset($this->tableColumns[$col]['val_fun']) && is_callable($this->tableColumns[$col]['val_fun']) && !call_user_func($this->tableColumns[$col]['val_fun'],$col,$val,$this->info))
				{
					$this->valError = true;
					$this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->valErrorStyles.'");');			
				}
				else if(isset($this->tableColumns[$col]['req']) && $this->tableColumns[$col]['req'] && strlen($val) == 0)
				{
					$this->valError = true;
					$this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->valErrorStyles.'");');			
				}
				else if(!isset($this->tableColumns[$col]['hidden_edit']) || !$this->tableColumns[$col]['hidden_edit'])
				{
					$this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->noValErrorStyles.'");');
				}
				$afterEditArray[$col] = $val;
				$val = $this->escapeData($val);			
				if(isset($this->tableColumns[$col]['mysql_edit_fun']))
				{
					$sets[] = $this->addTickMarks($col)." = ".$this->tableColumns[$col]['mysql_edit_fun']."('".$val."')";				
				}
				else
				{
					$sets[] = $this->addTickMarks($col)." = '".$val."'";
				}
			}
		}
		
		if($this->valError)
		{
			$this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->editRowTitle.'<div style="color: #f90d0d;">'.$this->langVars->errVal.'</div>');
		}
		else
		{
			if(count($sets) > 0)
			{
				foreach($idArr as $index => $id)
				{
					$idArr[$index] = $this->escapeData($id);
				}
				if($this->hasRightsToRows($idArr))
				{
					$query = "update $this->tableName set ".implode(', ',$sets)." where $this->primaryKeyCol in ('".implode("','",$idArr)."')";
					$result = $this->doQuery($query);
					if($result)
					{
						if(!empty($this->afterEditFun) && is_callable($this->afterEditFun))
						{
							call_user_func($this->afterEditFun,$idArr,$this->primaryKeyCol,$afterEditArray);
						}				
					}
				}
				else
				{
					$this->handleHacking();
				}
			}
		}
	}
	
	function updateRow()
	{
		$this->valError = false;
		$sets = array();
		$afterEditArray = array();
		$oldPrimaryKeyValue = $this->escapeData($this->info->old_primary_key_value);
        if($this->hasRightsToRow($oldPrimaryKeyValue))
        {
            unset($this->info->old_primary_key_value);
            $this->info = get_object_vars($this->info);
            foreach($this->info as $col => $val)
            {
                $col = str_replace($this->inputIdPrefix,'',$col);
                
                if(isset($this->tableColumns[$col]['on_edit_fun']) && is_callable($this->tableColumns[$col]['on_edit_fun']))
                {
                    $val = call_user_func($this->tableColumns[$col]['on_edit_fun'],$col,$val,$this->info);
                }
                // Check Validation
                if(isset($this->tableColumns[$col]['val_fun']) && is_callable($this->tableColumns[$col]['val_fun']) && !call_user_func($this->tableColumns[$col]['val_fun'],$col,$val,$this->info))
                {
                    $this->valError = true;
                    $this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->valErrorStyles.'");');			
                }
                else if(isset($this->tableColumns[$col]['req']) && $this->tableColumns[$col]['req'])
                {
					if(strlen($val) == 0)
					{
						$this->valError = true;
						$this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->valErrorStyles.'");');
					}
					else if(isset($this->tableColumns[$col]['calendar']) && substr($val,0,10) == '0000-00-00')
					{
						$this->valError = true;
						$this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->valErrorStyles.'");');
					}
                }
                else if(!isset($this->tableColumns[$col]['hidden_edit']) || !$this->tableColumns[$col]['hidden_edit'])
                {
                    $this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->noValErrorStyles.'");');
                }
                $val = $this->escapeData($val);			
                
                if(isset($this->tableColumns[$col]['mysql_edit_fun']))
                {
                    $sets[] = $this->addTickMarks($col)." = ".$this->tableColumns[$col]['mysql_edit_fun']."('".$val."')";				
                    $afterEditArray[$col] = $val;
                }
                else
                {
                    $sets[] = $this->addTickMarks($col)." = '".$val."'";
                    $afterEditArray[$col] = $val;
                }
            }
    
            if($this->valError)
            {
                $this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->editRowTitle.'<div style="color: #f90d0d;">'.$this->langVars->errVal.'</div>');
            }
            else
            {
                $query = "update $this->tableName set ".implode(', ',$sets)." where $this->primaryKeyCol = '$oldPrimaryKeyValue'";
                $result = $this->doQuery($query);
                if($result)
                {
                    if(!empty($this->afterEditFun) && is_callable($this->afterEditFun))
                    {
                        call_user_func($this->afterEditFun,$oldPrimaryKeyValue,$this->primaryKeyCol,$afterEditArray);
                    }				
                }
            }
        }
        else
        {
            $this->handleHacking();
        }
	}
	
	function insertRow()
	{
		$this->valError = false;
		$this->info = get_object_vars($this->info);
		$sets = array();
		$afterAddArray = array();
		foreach($this->info as $col => $val)
		{
			$col = str_replace($this->inputIdPrefix,'',$col);
			
			if(isset($this->tableColumns[$col]['on_add_fun']) && is_callable($this->tableColumns[$col]['on_add_fun']))
			{
				$val = call_user_func($this->tableColumns[$col]['on_add_fun'],$col,$val,$this->info);
			}
			// Check Validation
			if(isset($this->tableColumns[$col]['val_fun']) && is_callable($this->tableColumns[$col]['val_fun']) && !call_user_func($this->tableColumns[$col]['val_fun'],$col,$val,$this->info))
			{
				$this->valError = true;
				$this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->valErrorStyles.'");');			
			}
			else if(isset($this->tableColumns[$col]['req']) && $this->tableColumns[$col]['req'])
			{
				if(strlen($val) == 0)
				{
					$this->valError = true;
					$this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->valErrorStyles.'");');
				}
				else if(isset($this->tableColumns[$col]['calendar']) && substr($val,0,10) == '0000-00-00')
				{
					$this->valError = true;
					$this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->valErrorStyles.'");');
				}
			}
			else if(!isset($this->tableColumns[$col]['hidden_add']) || !$this->tableColumns[$col]['hidden_add'])
			{
				$this->retArr[] = array('where' => 'javascript', 'value' => '$("'.$col.'_label_cell").setStyle("'.$this->noValErrorStyles.'");');
			}
			$val = $this->escapeData($val);			
			
			if(isset($this->tableColumns[$col]['mysql_add_fun']))
			{
				$sets[] = $this->addTickMarks($col)." = ".$this->tableColumns[$col]['mysql_add_fun']."('".$val."')";				
				$afterAddArray[$col] = $val;
			}
			else
			{
				$sets[] = $this->addTickMarks($col)." = '".$val."'";
				$afterAddArray[$col] = $val;
			}
		}

		if($this->valError)
		{
			$this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->addRowTitle.'<div style="color: #f90d0d;">Please correct the fields in red</div>');
		}
		else
		{
			$query = "insert into $this->tableName set ".implode(', ',$sets);
			$result = $this->doQuery($query);
			if($result)
			{
				if(!empty($this->afterAddFun) && is_callable($this->afterAddFun))
				{
					call_user_func($this->afterAddFun,mysql_insert_id(),$this->primaryKeyCol,$afterAddArray);
				}
			}
		}
	}
	
	function addRow()
	{
		if(stristr($this->permissions,'A'))
		{
			$html = '';
			$html .= '<form id="'.$this->varPrefix.'_add_form" name="'.$this->varPrefix.'_add_form"><table '.$this->addTableInfo.'>';
			$jsAddFun = 'addRow(\''.$this->varPrefix.'\');';
			$calJs = array();
			foreach($this->tableColumns as $col => $info)
			{
				if(stristr($info['perms'],'A') && !isset($info['join']['real_column']))
				{
					$defVal = isset($info['default']) ? $info['default'] : '';
					$inputInfo = isset($info['input_info']) ? $info['input_info'] : '';
					$inputHtml = '';
					
					if(isset($info['add_fun']) && is_callable($info['add_fun']))
					{
						$defVal = call_user_func($info['add_fun'],$col,$defVal,$inputInfo);
					}
					if(isset($info['format_input_fun']) && is_callable($info['format_input_fun']))
					{
						$inputHtml .= call_user_func($info['format_input_fun'],$col,$defVal,$inputInfo);
					}
					else if(isset($info['hidden_add']) && $info['hidden_add'])
					{
						$inputHtml .= '<input type="hidden" id="'.$this->inputIdPrefix.$col.'" value="'.htmlspecialchars($defVal).'" '.$inputInfo.' />';						
					}
					else if(isset($info['select_query']))
					{
						$inputHtml .= $this->getSelect($info['select_query'],$this->inputIdPrefix.$col,$defVal,$inputInfo);					
					}
					else if(isset($info['select_array']) && is_array($info['select_array']))
					{
						$inputHtml .= $this->getSelectFromArray($info['select_array'],$this->inputIdPrefix.$col,$defVal,$inputInfo);					
					}
					else if(isset($info['textarea']))
					{
						$numRows = isset($info['textarea']['rows']) ? $info['textarea']['rows'] : 7;
						$numCols = isset($info['textarea']['cols']) ? $info['textarea']['cols'] : 25;
						$inputHtml .= '<textarea id="'.$this->inputIdPrefix.$col.'" rows="'.$numRows.'" cols="'.$numCols.'" value="'.htmlspecialchars($defVal).'" '.$inputInfo.' >'.htmlspecialchars($defVal).'</textarea>';						
					}
					else if(isset($info['checkbox']))
					{
						$checkedValue = isset($info['checkbox']['checked_value']) ? $info['checkbox']['checked_value'] : '';
						$unCheckedValue = isset($info['checkbox']['un_checked_value']) ? $info['checkbox']['un_checked_value'] : '';
						$checked = $defVal == $checkedValue ? 'checked="checked"' : '';
						$inputHtml .= '<input type="checkbox" id="'.$this->inputIdPrefix.$col.'" value="'.$defVal.'" onclick="updateCheckBoxValue(this,\''.$checkedValue.'\',\''.$unCheckedValue.'\');" '.$checked.'  '.$inputInfo.' />';
					}
					else if(isset($info['join']) && is_array($info['join']))
					{
						$joinTable = !empty($info['join']['db']) ? $info['join']['db'].'.'.$info['join']['table'] : $info['join']['table'];
						$info['join']['display_mask'] = isset($info['join']['display_mask']) ? $info['join']['display_mask'] : $joinTable.'.'.$info['join']['column'];
						$selectQuery = 'select distinct('.$info['join']['column'].'), '.$info['join']['display_mask'].' from '.$joinTable.' order by '.$info['join']['display_mask'].' asc';
						$inputHtml .= $this->getSelect($selectQuery,$this->inputIdPrefix.$col,$defVal,$inputInfo);
					}
					else if(isset($info['calendar']))
					{
						$inputHtml .= '<span><input type="hidden" id="'.$this->inputIdPrefix.$col.'" value="'.htmlspecialchars($defVal).'" /></span>';						
					}
					else
					{
						$maxLength = isset($info['maxlen']) ? 'maxlength="'.$info['maxlen'].'"' : '';
						$inputHtml .= '<input type="text" id="'.$this->inputIdPrefix.$col.'" value="'.htmlspecialchars($defVal).'" '.$maxLength.' onKeyPress="if(enterPressed(event)){'.$jsAddFun.' return false;}" '.$inputInfo.' />';
					}
					if(isset($info['hidden_add']) && $info['hidden_add'])
					{
						$html .= $inputHtml;
					}
					else
					{	
						if(isset($info['req']) && $info['req'])
						{
							$inputHtml .= $this->reqMarker;
						}
						$html .= '<tr><td id="'.$col.'_label_cell" class="labelCell"><label for="'.$this->inputIdPrefix.$col.'">'.$info['display_text'].':</label></td><td id="'.$col.'_input_cell" class="inputCell">'.$inputHtml.'</td></tr>';
					}
					if(isset($info['calendar']))
					{
						$calJs[] = $this->getCalJs($info['calendar'],$col);
					}
				}
			}
			$html .= '</table></form><div id="addRowButtons"><button class="ajaxButton" onclick="'.$jsAddFun.'">'.$this->langVars->btnAdd.'</button><button class="ajaxButton" onclick="toAjaxTableEditor(\'update_html\',\'\');">'.$this->langVars->btnCancel.'</button></div>';
			$this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->addRowTitle);
			$this->retArr[] = array('layer_id' => 'recordLayer', 'where' => 'innerHTML', 'value' => '');		
			$this->retArr[] = array('layer_id' => 'searchButtonsLayer', 'where' => 'innerHTML', 'value' => '');		
			$this->retArr[] = array('layer_id' => 'tableLayer', 'where' => 'innerHTML', 'value' => $html);
			if(count($calJs) > 0)
			{
				$this->retArr[] = array('where' => 'javascript', 'value' => implode("\n",$calJs));
			}
			//$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
			
			if(is_callable($this->addScreenFun))
			{
				call_user_func($this->addScreenFun);
			}
		}	
	}

	function editMultRows()
	{
		if(stristr($this->permissions,'E'))
		{
			if($this->allowEditMult)
			{
				$html = '';
				$calJs = array();
				$this->info = get_object_vars($this->info);
				$jsUpdateFun = "updateMultRows(new Array('".implode("','",$this->info)."'),'$this->varPrefix');";
				$id = $this->escapeData(current($this->info));
				$query = "select * from $this->tableName where $this->primaryKeyCol = '$id'";
				$result = $this->doQuery($query);
				if($row = mysql_fetch_assoc($result))
				{
					$html .= '<form id="'.$this->varPrefix.'_edit_form" name="'.$this->varPrefix.'_edit_form"><table '.$this->editTableInfo.'>';
					foreach($this->tableColumns as $col => $info)
					{
						if(stristr($info['perms'],'E') && !isset($info['join']['real_column']) && $col != $this->primaryKeyCol)
						{
							if(isset($row[$col]) && strlen($row[$col]) > 0)
							{
								$defVal =  $row[$col];
							}
							else if(isset($info['default']))
							{
								$defVal = $info['default'];
							}
							else
							{
								$defVal = '';
							}
							$inputInfo = isset($info['input_info']) ? $info['input_info'] : '';
							$inputInfo .= ' disabled="disabled"';
							$inputHtml = '';
							
							if(isset($info['edit_fun']) && is_callable($info['edit_fun']))
							{
								$defVal = call_user_func($info['edit_fun'],$col,$defVal,$row);
							}
							if(isset($info['format_input_fun']) && is_callable($info['format_input_fun']))
							{
								$inputHtml .= call_user_func($info['format_input_fun'],$col,$defVal,$row,$inputInfo);
							}
							else if(isset($info['hidden_edit']) && $info['hidden_edit'])
							{
								$inputHtml .= '<input type="hidden" id="'.$this->inputIdPrefix.$col.'" value="'.htmlspecialchars($defVal).'" '.$inputInfo.' />';						
							}
							else if(isset($info['select_query']))
							{
								$inputHtml .= $this->getSelect($info['select_query'],$this->inputIdPrefix.$col,$defVal,$inputInfo);			
							}
							else if(isset($info['select_array']) && is_array($info['select_array']))
							{
								$inputHtml .= $this->getSelectFromArray($info['select_array'],$this->inputIdPrefix.$col,$defVal,$inputInfo);					
							}
							else if(isset($info['textarea']))
							{
								$numRows = isset($info['textarea']['rows']) ? $info['textarea']['rows'] : 7;
								$numCols = isset($info['textarea']['cols']) ? $info['textarea']['cols'] : 25;
								$inputHtml .= '<textarea id="'.$this->inputIdPrefix.$col.'" rows="'.$numRows.'" cols="'.$numCols.'" value="'.htmlspecialchars($defVal).'" '.$inputInfo.' >'.htmlspecialchars($defVal).'</textarea>';
							}
							else if(isset($info['checkbox']))
							{
								$checkedValue = isset($info['checkbox']['checked_value']) ? $info['checkbox']['checked_value'] : '';
								$unCheckedValue = isset($info['checkbox']['un_checked_value']) ? $info['checkbox']['un_checked_value'] : '';
								$checked = $defVal == $checkedValue ? 'checked="checked"' : '';
								$inputHtml .= '<input type="checkbox" id="'.$this->inputIdPrefix.$col.'" value="'.$defVal.'" onclick="updateCheckBoxValue(this,\''.$checkedValue.'\',\''.$unCheckedValue.'\');" '.$checked.'  '.$inputInfo.' />';
							}
							else if(isset($info['join']) && is_array($info['join']))
							{
								$joinTable = !empty($info['join']['db']) ? $info['join']['db'].'.'.$info['join']['table'] : $info['join']['table'];
								$info['join']['display_mask'] = isset($info['join']['display_mask']) ? $info['join']['display_mask'] : $joinTable.'.'.$info['join']['column'];
								$selectQuery = 'select distinct('.$info['join']['column'].'), '.$info['join']['display_mask'].' from '.$joinTable.' order by '.$info['join']['display_mask'].' asc';
								$inputHtml .= $this->getSelect($selectQuery,$this->inputIdPrefix.$col,$defVal,$inputInfo);
							}
							else if(isset($info['calendar']))
							{
								$inputHtml .= '<span><input type="hidden" id="'.$this->inputIdPrefix.$col.'" value="'.htmlspecialchars($defVal).'" /></span>';						
							}
							else
							{
								$maxLength = isset($info['maxlen']) ? 'maxlength="'.$info['maxlen'].'"' : '';
								$inputHtml .= '<input type="text" id="'.$this->inputIdPrefix.$col.'" value="'.htmlspecialchars($defVal).'" '.$maxLength.' onKeyPress="if(enterPressed(event)){'.$jsUpdateFun.' return false;}" '.$inputInfo.' />';
							}
							if(isset($info['hidden_edit']) && $info['hidden_edit'])
							{
								$html .= $inputHtml;
							}
							else
							{	
								if(isset($info['req']) && $info['req'])
								{
									$inputHtml .= '<span id="'.$col.'_req_mark" style="display: none;">'.$this->reqMarker.'</span>';
								}
								$html .= '<tr>';
								$html .= '<td id="'.$col.'_label_cell" class="labelCell"><label for="'.$this->inputIdPrefix.$col.'">'.$info['display_text'].':</label></td>';
								$html .= '<td id="'.$col.'_input_cell" class="inputCell">'.$inputHtml.'</td>';
								$html .= '<td><input type="checkbox" id="'.$this->inputIdPrefix.$col.'_em_cb" onchange="disableEnableInput(\''.$this->inputIdPrefix.$col.'\',this);" /></td>';
								$html .= '</tr>';
							}
							if(isset($info['calendar']))
							{
								$calJs[] = $this->getCalJs($info['calendar'],$col);
							}
						}
					}
					$html .= '</table></form><div id="editRowButtons"><button class="ajaxButton" onclick="'.$jsUpdateFun.'">'.$this->langVars->btnUpdate.'</button><button class="ajaxButton" onclick="toAjaxTableEditor(\'update_html\',\'\');">'.$this->langVars->btnCancel.'</button></div>';
				}
				$this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->editMultTitle);
				$this->retArr[] = array('layer_id' => 'recordLayer', 'where' => 'innerHTML', 'value' => '');		
				$this->retArr[] = array('layer_id' => 'searchButtonsLayer', 'where' => 'innerHTML', 'value' => '');		
				$this->retArr[] = array('layer_id' => 'tableLayer', 'where' => 'innerHTML', 'value' => $html);
				if(count($calJs) > 0)
				{
					$this->retArr[] = array('where' => 'javascript', 'value' => implode("\n",$calJs));
				}
				//$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
				
				if(is_callable($this->editScreenFun))
				{
					call_user_func($this->editScreenFun);
				}
			}
			else
			{
				$this->warnings[] = $this->langVars->edit1Row;
			}
		}
	}
	
	function editRow()
	{
		if(stristr($this->permissions,'E'))
		{
			$html = '';
			$calJs = array();
			$id = $this->escapeData($this->info);
			$jsUpdateFun = 'updateRow(\''.$id.'\',\''.$this->varPrefix.'\');';
			$query = "select * from $this->tableName where $this->primaryKeyCol = '$id'";
			$result = $this->doQuery($query);
			if($row = mysql_fetch_assoc($result))
			{
				$html .= '<form id="'.$this->varPrefix.'_edit_form" name="'.$this->varPrefix.'_edit_form"><table '.$this->editTableInfo.'>';
				foreach($this->tableColumns as $col => $info)
				{
					if(stristr($info['perms'],'E') && !isset($info['join']['real_column']))
					{
						if(isset($row[$col]) && strlen($row[$col]) > 0)
						{
							// If it is a calendar and there is a default and no date use the default
							if(isset($info['default']) && isset($info['calendar']) && $info['calendar'] && substr($row[$col],0,10) == '0000-00-00')
							{
								$defVal = $info['default'];
							}
							else
							{
								$defVal =  $row[$col];
							}
						}
						else if(isset($info['default']))
						{
							$defVal = $info['default'];
						}
						else
						{
							$defVal = '';
						}
						$inputInfo = isset($info['input_info']) ? $info['input_info'] : '';
						$inputHtml = '';
						
						if(isset($info['edit_fun']) && is_callable($info['edit_fun']))
						{
							$defVal = call_user_func($info['edit_fun'],$col,$defVal,$row);
						}
						if(isset($info['format_input_fun']) && is_callable($info['format_input_fun']))
						{
							$inputHtml .= call_user_func($info['format_input_fun'],$col,$defVal,$row,$inputInfo);
						}
						else if(isset($info['hidden_edit']) && $info['hidden_edit'])
						{
							$inputHtml .= '<input type="hidden" id="'.$this->inputIdPrefix.$col.'" value="'.htmlspecialchars($defVal).'" '.$inputInfo.' />';						
						}
						else if(isset($info['select_query']))
						{
							$inputHtml .= $this->getSelect($info['select_query'],$this->inputIdPrefix.$col,$defVal,$inputInfo);			
						}
						else if(isset($info['select_array']) && is_array($info['select_array']))
						{
							$inputHtml .= $this->getSelectFromArray($info['select_array'],$this->inputIdPrefix.$col,$defVal,$inputInfo);					
						}
						else if(isset($info['textarea']))
						{
							$numRows = isset($info['textarea']['rows']) ? $info['textarea']['rows'] : 7;
							$numCols = isset($info['textarea']['cols']) ? $info['textarea']['cols'] : 25;
							$inputHtml .= '<textarea id="'.$this->inputIdPrefix.$col.'" rows="'.$numRows.'" cols="'.$numCols.'" value="'.htmlspecialchars($defVal).'" '.$inputInfo.' >'.htmlspecialchars($defVal).'</textarea>';
						}
						else if(isset($info['checkbox']))
						{
							$checkedValue = isset($info['checkbox']['checked_value']) ? $info['checkbox']['checked_value'] : '';
							$unCheckedValue = isset($info['checkbox']['un_checked_value']) ? $info['checkbox']['un_checked_value'] : '';
							$checked = $defVal == $checkedValue ? 'checked="checked"' : '';
							$inputHtml .= '<input type="checkbox" id="'.$this->inputIdPrefix.$col.'" value="'.$defVal.'" onclick="updateCheckBoxValue(this,\''.$checkedValue.'\',\''.$unCheckedValue.'\');" '.$checked.' '.$inputInfo.' />';
						}
						else if(isset($info['join']) && is_array($info['join']))
						{
							$joinTable = !empty($info['join']['db']) ? $info['join']['db'].'.'.$info['join']['table'] : $info['join']['table'];
							$info['join']['display_mask'] = isset($info['join']['display_mask']) ? $info['join']['display_mask'] : $joinTable.'.'.$info['join']['column'];
							$selectQuery = 'select distinct('.$info['join']['column'].'), '.$info['join']['display_mask'].' from '.$joinTable.' order by '.$info['join']['display_mask'].' asc';
							$inputHtml .= $this->getSelect($selectQuery,$this->inputIdPrefix.$col,$defVal,$inputInfo);
						}
						else if(isset($info['calendar']))
						{
							$inputHtml .= '<span><input type="hidden" id="'.$this->inputIdPrefix.$col.'" value="'.htmlspecialchars($defVal).'" /></span>';						
						}
						else
						{
							$maxLength = isset($info['maxlen']) ? 'maxlength="'.$info['maxlen'].'"' : '';
							$inputHtml .= '<input type="text" id="'.$this->inputIdPrefix.$col.'" value="'.htmlspecialchars($defVal).'" '.$maxLength.' onKeyPress="if(enterPressed(event)){'.$jsUpdateFun.' return false;}" '.$inputInfo.' />';
						}
						if(isset($info['hidden_edit']) && $info['hidden_edit'])
						{
							$html .= $inputHtml;
						}
						else
						{	
							if(isset($info['req']) && $info['req'])
							{
								$inputHtml .= $this->reqMarker;
							}
							$html .= '<tr><td id="'.$col.'_label_cell" class="labelCell"><label for="'.$this->inputIdPrefix.$col.'">'.$info['display_text'].':</label></td><td id="'.$col.'_input_cell" class="inputCell">'.$inputHtml.'</td></tr>';
						}
						if(isset($info['calendar']))
						{
							$calJs[] = $this->getCalJs($info['calendar'],$col);
						}
					}
				}
				$html .= '</table></form><div id="editRowButtons"><button class="ajaxButton" onclick="'.$jsUpdateFun.'">'.$this->langVars->btnUpdate.'</button><button class="ajaxButton" onclick="toAjaxTableEditor(\'update_html\',\'\');">'.$this->langVars->btnCancel.'</button></div>';
			}
			$this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->editRowTitle);
			$this->retArr[] = array('layer_id' => 'recordLayer', 'where' => 'innerHTML', 'value' => '');		
			$this->retArr[] = array('layer_id' => 'searchButtonsLayer', 'where' => 'innerHTML', 'value' => '');		
			$this->retArr[] = array('layer_id' => 'tableLayer', 'where' => 'innerHTML', 'value' => $html);
			if(count($calJs) > 0)
			{
				$this->retArr[] = array('where' => 'javascript', 'value' => implode("\n",$calJs));
			}
			//$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
			
			if(is_callable($this->editScreenFun))
			{
				call_user_func($this->editScreenFun);
			}
		}		
	}
	
	function getCalJs($calInfo,$col)
	{
		if(is_array($calInfo))
		{
			$dateFormat = isset($calInfo['format']) ? $calInfo['format'] : $this->defaultJsCalFormat;
			$resetDate = isset($calInfo['reset']) ? 'true' : 'null';
			$extraInfo = isset($calInfo['extra_info']) ? $calInfo['extra_info'] : 'null';
		}
		else
		{
			// Maintain backwards compatibility
			$dateFormat = is_string($calInfo) ? $calInfo : $this->defaultJsCalFormat;
			$resetDate = 'null';
			$extraInfo = 'null';
		}
		return 'prepareForCalendar(document.getElementById(\''.$this->inputIdPrefix.$col.'\'),\''.$this->inputIdPrefix.$col.'\',\''.$dateFormat.'\','.$resetDate.','.$extraInfo.');';
	}
	
	function deleteRow()
	{
		if(stristr($this->permissions,'D'))
		{
			$id = $this->escapeData($this->info);
            if($this->hasRightsToRow($id))
            {
                $query = "delete from $this->tableName where $this->primaryKeyCol = '$id'";
                $result = $this->doQuery($query);
                if($result)
                {
                    if(isset($this->afterDeleteFun) && is_callable($this->afterDeleteFun))
                    {
                        call_user_func($this->afterDeleteFun,$id,$this->primaryKeyCol);
                    }
                    $this->updateHtml();
                }
            }
            else
            {
                $this->handleHacking();
            }
		}
	}
	
	function deleteMultRows()
	{
		if(stristr($this->permissions,'D'))
		{
			foreach($this->info as $id)
			{
				$id = $this->escapeData($id);
				if($this->hasRightsToRow($id))
                {
                    $query = "delete from $this->tableName where $this->primaryKeyCol = '$id'";
                    $result = $this->doQuery($query);
                    if($result)
                    {
                        if(isset($this->afterDeleteFun) && is_callable($this->afterDeleteFun))
                        {
                            call_user_func($this->afterDeleteFun,$id,$this->primaryKeyCol);
                        }
                    }
                }
                else
                {
                    $this->handleHacking();
                }
			}
			$this->updateHtml();
		}
	}
	
	function updateHtml()
	{
		$this->displayTable();
		$this->displayBottomInfo();
		$this->retArr[] = array('layer_id' => 'titleLayer', 'where' => 'innerHTML', 'value' => $this->tableTitle);
        //$this->retArr[] = array('where' => 'javascript', 'value' => "resetScrollTop();");
	}
	
	function displayBottomInfo()
	{
		$html = '<div style="padding: 5px;">'.$this->langVars->lblPage.' '.$this->getPageDropDown().'&nbsp;';
		if(stristr($this->permissions,'U'))
		{
			$html .= $this->langVars->lblDisplay.' '.$this->getDispNumDropDown().'&nbsp;';
		}
		if(stristr($this->permissions,'A'))
		{
			$html .= '<button onclick="toAjaxTableEditor(\'add_row\',\'\');">'.$this->langVars->btnAdd.'</button>&nbsp;';
		}
		if(stristr($this->permissions,'E') && !stristr($this->permissions,'I'))
		{
			$html .= '<button onclick="editCopyViewDelete(\''.$this->varPrefix.'\',\'edit_row\');">'.$this->langVars->btnEdit.'</button>&nbsp;';
		}
		if(stristr($this->permissions,'V') && !stristr($this->permissions,'I'))
		{
			$html .= '<button onclick="editCopyViewDelete(\''.$this->varPrefix.'\',\'view_row\');">'.$this->langVars->btnView.'</button>&nbsp;';
		}
		if(stristr($this->permissions,'C') && !stristr($this->permissions,'I'))
		{
			$html .= '<button onclick="editCopyViewDelete(\''.$this->varPrefix.'\',\'copy_mult_rows\');">'.$this->langVars->btnCopy.'</button>&nbsp;';
		}
		if(stristr($this->permissions,'D') && !stristr($this->permissions,'I'))
		{
			$html .= '<button onclick="editCopyViewDelete(\''.$this->varPrefix.'\',\'delete_mult_rows\');">'.$this->langVars->btnDelete.'</button>&nbsp;';
		}
		if(stristr($this->permissions,'X'))
		{
			$html .= '<button onclick="window.location=\''.$_SERVER['PHP_SELF'].'?export=1\'">'.$this->langVars->btnExport.'</button>&nbsp;';
		}
		if(stristr($this->permissions,'H'))
		{
			$html .= '<button onclick="toAjaxTableEditor(\'show_hide_columns\',\'\');">'.$this->langVars->btnShowHide.'</button>&nbsp;';		
		}
		if(stristr($this->permissions,'O'))
		{
			$html .= '<button onclick="toAjaxTableEditor(\'order_columns_screen\',\'\');">'.$this->langVars->btnOrderCols.'</button>&nbsp;';		
		}
        if(stristr($this->permissions,'S') && $this->searchType == 'quick')
		{
			$html .= '<button onclick="toAjaxTableEditor(\'show_advanced_search\',\'\');">'.$this->langVars->btnASearch.'</button>&nbsp;';
		}
		else if(stristr($this->permissions,'Q') && $this->searchType == 'advanced')
		{
			$html .= '<button onclick="toAjaxTableEditor(\'show_quick_search\',\'\');">'.$this->langVars->btnQSearch.'</button>&nbsp;';		
		}
		foreach($this->userButtons as $key => $info)
		{
			if(isset($info['button_html']))
			{
				$html .= $info['button_html'].'&nbsp;';
			}
			else if(isset($info['button_info']))
			{
				$html .= '<button '.$info['button_info'].'>'.$info['label'].'</button>&nbsp;';			
			}
			else if(isset($info['call_back_fun']) && is_callable($info['call_back_fun']) && stristr($this->permissions,'M'))
			{
				$confirmMsg = isset($info['confirm_msg']) ? $info['confirm_msg'] : '';
				$html .= '<button onclick="userButtonClicked(\''.$this->varPrefix.'\',\''.$key.'\',\''.$confirmMsg.'\')">'.$info['label'].'</button>&nbsp;';
			}
		}
		
		$html .= '</div>';
		if(stristr($this->permissions,'Q') && $this->searchType == 'quick')
		{	
			$html .= $this->langVars->lblSearch.': <input type="text" id="searchString" value="'.$this->searchString.'" size="25" onKeyPress="if(enterPressed(event)){handleSearch(); return false;}" />&nbsp;<button onclick="handleSearch();">'.$this->langVars->lblSearch.'</button>&nbsp;<button onclick="clearSearch();">'.$this->langVars->btnCSearch.'</button>';
		}
		else if(stristr($this->permissions,'S') && $this->searchType == 'advanced')
		{
			$html .= $this->getAdvancedSearchHtml();
		}	
		$this->retArr[] = array('layer_id' => 'searchButtonsLayer', 'where' => 'innerHTML', 'value' => $html);		
	}	
	
	function displayTable()
	{
        $this->unsetHiddenColumns();
        $this->setColumnOrder();
        $this->setPagingVars();
        $this->formatJoinClause();
        $this->formatSelectClause();
        $this->formatWhereClause();
		$this->setNumResults();
		$this->displayTableHtml();
	}
	
	function formatSelectClause()
	{
        $sets = array();
        foreach($this->tableColumns as $col => $info)
        {
            if(isset($info['join']) && is_array($info['join']))
            {
                $info['join']['display_mask'] = isset($info['join']['display_mask']) ? $info['join']['display_mask'] : $info['join']['alias'].'.'.$info['join']['column'];
                $sets[] = $info['join']['display_mask'].' as '.$this->addTickMarks($col);
            }
            else if(isset($info['display_mask']))
            {
                $sets[] = $info['display_mask'].' as '.$this->addTickMarks($col);
            }
            else
            {
                $sets[] = $this->addTickMarks($this->dbAndTable.'.'.$col);
            }
        }
        $this->selectClause = 'select '.implode(', '.$this->queryLineBreak,$sets).$this->queryLineBreak.'from '.$this->dbAndTable.$this->queryLineBreak;
	}
    
	function formatJoinClause()
	{
        $sets = array();
        $counter = 0;
        $joins = array();
        foreach($this->tableColumns as $col => $info)
        {
            $saveJoin = true;
            if(isset($info['join']) && is_array($info['join']))
            {
                if($col == $this->primaryKeyCol && isset($info['join']['display_mask']))
                {
                    $this->warnings[] = 'Joining on the primary key with a display mask is not supported.';
                }
                // Set join table var, if there is a database concat with table
                $joinTable = !empty($info['join']['db']) ? $info['join']['db'].'.'.$info['join']['table'] : $info['join']['table'];
                // Set join type
                $joinType = isset($info['join']['type']) ? $info['join']['type'] : 'left';
                // Set join column
                $joinColumn = isset($info['join']['real_column']) ? $info['join']['real_column'] : $col;
                // Set foriegn join column
                $foriegnJoinColumn = $info['join']['column'];
                // Set join identifier
                $joinIdentifier = $joinTable.$joinColumn.$foriegnJoinColumn.$joinType;
                if(isset($joins[$joinIdentifier]))
                {
                    // If this table was joined before, get the old alias and set save join to false
                    $this->tableColumns[$col]['join']['alias'] = $joins[$joinIdentifier];
                    $joinAlias = $this->tableColumns[$col]['join']['alias'];
                    $saveJoin = false;
                }
                else
                {
                    // If this is a new join and there is no user defined alias create one.
					if(!isset($this->tableColumns[$col]['join']['alias']))
					{
						$this->tableColumns[$col]['join']['alias'] = $info['join']['table'].'_'.substr(md5(uniqid(rand(),1)), 3, 5).'_'.$counter;
					}
                    $joinAlias = $this->tableColumns[$col]['join']['alias'];
                    // Store join and alias
                    $joins[$joinIdentifier] = $joinAlias;
                }
				// Replace old table and database with alias (this must be done before the select clause is formatted)
                // Use preg replace so the substitution only happens once on each table/column definition (this is prevent errors when column names contain the table names)
                // Need to use explode/implode for concatenating columns
				if(isset($info['join']['display_mask']))
				{
					$this->tableColumns[$col]['join']['display_mask'] = implode(',',preg_replace('/'.preg_quote($joinTable).'/',preg_quote($joinAlias),explode(',',$info['join']['display_mask']),1));
					$this->tableColumns[$col]['join']['display_mask'] = implode(',',preg_replace('/'.preg_quote($info['join']['table']).'/',preg_quote($joinAlias),explode(',',$info['join']['display_mask']),1));
				}
				else
				{
					$this->tableColumns[$col]['join']['display_mask'] = $info['join']['column'];
				}
                if($saveJoin)
                {                
                    $sets[] = $joinType.' join '.$this->addTickMarks($joinTable).' as '.$this->addTickMarks($joinAlias).' on '.$this->addTickMarks($this->dbAndTable.'.'.$joinColumn).' = '.$this->addTickMarks($joinAlias.'.'.$foriegnJoinColumn);
                    $counter++;
                }
            }
        }
        if(!empty($sets))
		{
			$this->joinClause = implode($this->queryLineBreak,$sets).$this->queryLineBreak;
		}
	}
	
	function formatWhereClause()
	{
        $sets = array();
        $whereClause = '';
        // Quick search
        if(!empty($this->searchString) && $this->searchType == 'quick')
        {
            foreach($this->tableColumns as $col => $info)
            {
                if(stristr($info['perms'],'Q'))
                {
                    if(isset($info['join']) && is_array($info['join']))
                    {
                        $joinTable = !empty($info['join']['db']) ? $info['join']['db'].'.'.$info['join']['table'] : $info['join']['table'];
                        $info['join']['display_mask'] = isset($info['join']['display_mask']) ? $info['join']['display_mask'] : $joinTable.'.'.$info['join']['column'];
                        $sets[] = $info['join']['display_mask']." like '%".$this->escapeData($this->searchString)."%'";
                    }
                    else if(isset($info['display_mask']))
                    {
                        $sets[] = $info['display_mask']." like '%".$this->escapeData($this->searchString)."%'";
                    }
                    else
                    {
                        $sets[] = $this->addTickMarks($this->dbAndTable.'.'.$col)." like '%".$this->escapeData($this->searchString)."%'";
                    }
                }
            }
        }
        // Advanced search
        else if(!empty($this->advSearches) && $this->searchType == 'advanced')
        {
            foreach($this->advSearches as $i => $asInfo)
            {
                if(!empty($asInfo['cols']) && stristr($this->tableColumns[$asInfo['cols']]['perms'],'S'))
                {
                    if(!isset($this->opts[$asInfo['opts']]))
                    {
                        // Unknown search operator
                        $this->handleHacking();
                        continue;
                    }
                    $asInfo['strs'] = $this->escapeData($asInfo['strs']);
                    if(isset($this->tableColumns[$asInfo['cols']]['join']) && is_array($this->tableColumns[$asInfo['cols']]['join']))
                    {
                        $info = $this->tableColumns[$asInfo['cols']];
                        
                        $joinTable = !empty($info['join']['db']) ? $info['join']['db'].'.'.$info['join']['table'] : $info['join']['table'];
                        $info['join']['display_mask'] = isset($info['join']['display_mask']) ? $info['join']['display_mask'] : $joinTable.'.'.$info['join']['column'];
                                                
                        if($asInfo['opts'] == 'like' || $asInfo['opts'] == 'not like')
                            $sets[] = $info['join']['display_mask'].' '.$asInfo['opts']." '%".$asInfo['strs']."%'";
                        else
                            $sets[] = $info['join']['display_mask'].' '.$asInfo['opts']." '".$asInfo['strs']."'";
                    }
                    else if(isset($this->tableColumns[$asInfo['cols']]['display_mask']))
                    {
                        $displayMask = $this->tableColumns[$asInfo['cols']]['display_mask'];
                        if($asInfo['opts'] == 'like' || $asInfo['opts'] == 'not like')
                            $sets[] = $displayMask.' '.$asInfo['opts']." '%".$asInfo['strs']."%'";
                        else
                            $sets[] = $displayMask.' '.$asInfo['opts']." '".$asInfo['strs']."'";
                    }
                    else
                    {
                        if($asInfo['opts'] == 'like' || $asInfo['opts'] == 'not like')
                            $sets[] = $this->addTickMarks($this->dbAndTable.'.'.$asInfo['cols']).' '.$asInfo['opts']." '%".$asInfo['strs']."%'";
                        else
                            $sets[] = $this->addTickMarks($this->dbAndTable.'.'.$asInfo['cols']).' '.$asInfo['opts']." '".$asInfo['strs']."'";
                    }
                }
            }
        }
        
        $glue = 'or';
        if($this->searchType == 'advanced' && $this->matchAll)
        {
            $glue = 'and';
        }
        if(!empty($sets))
        {
            $whereClause .= '('.implode(' '.$glue.$this->queryLineBreak,$sets).')';
        }
        // Format data filters
        $sets2 = array();
        foreach($this->tableColumns as $col => $info)
        {
            $sets3 = array();
            if(isset($info['data_filters']))
            {
                if(isset($info['join']['display_mask']))
                {
                    foreach($info['data_filters']['filters'] as $df)
                    {
                        $sets3[] = $info['join']['display_mask'].' '.$df;
                    }
                }
                else
                {
                    $tableName = empty($this->dbName) ? $this->tableName : $this->dbName.'.';
                    foreach($info['data_filters']['filters'] as $df)
                    {
                        $sets3[] = $this->addTickMarks($tableName.'.'.$col).' '.$df;
                    }
                }
                if(isset($info['data_filters']['criteria']) && $info['data_filters']['criteria'] == 'any')
                {
                    $sets2[] = '('.implode(' or'.$this->queryLineBreak,$sets3).')';
                }
                else
                {
                    $sets2[] = implode(' and'.$this->queryLineBreak,$sets3);
                }
            }
        }

        if(!empty($sets2))
        {
            if(empty($whereClause))
            {
                $whereClause .= ' '.implode(' and '.$this->queryLineBreak,$sets2);
            }
            else
            {
                $whereClause .= ' and '.$this->queryLineBreak.implode(' and '.$this->queryLineBreak,$sets2);
            }
        }
        if(!empty($whereClause))
        {
            $this->whereClause .= ' where '.$whereClause.$this->queryLineBreak;
        }
		if(!empty($this->sqlFilters))
		{
			if(empty($this->whereClause))
			{
				$this->whereClause .= 'where '.$this->sqlFilters.$this->queryLineBreak;
			}
			else
			{
				$this->whereClause .= ' and '.$this->sqlFilters.$this->queryLineBreak;
			}
		}
	}
	
	function setNumResults()
	{
		$this->numResults = 0;
		$query = 'select count(*) as num_results from '.$this->tableName.' '.$this->joinClause.' '.$this->whereClause;
		$result = $this->doQuery($query);
		if($row = mysql_fetch_assoc($result))
		{
			$this->numResults = $row['num_results'];
		}
	}
    
	function setPagingVars()
	{
		if(isset($_SESSION[$this->varPrefix.'start']))
		{
			$this->start = $_SESSION[$this->varPrefix.'start'];
		}
		if(isset($_SESSION[$this->varPrefix.'obc']))
		{
			$this->orderByColumn = $_SESSION[$this->varPrefix.'obc'];
		}
		if(isset($_SESSION[$this->varPrefix.'aod']))
		{
			$this->ascOrDesc = $_SESSION[$this->varPrefix.'aod'];
		}
		if(isset($_SESSION[$this->varPrefix.'ss']))
		{
			$this->searchString = $_SESSION[$this->varPrefix.'ss'];
		}
	}
	
	function displayTableHtml()
	{
		$html = '';
		$numRows = 0;
		$this->extraOrderByInfo = empty($this->extraOrderByInfo) ? '' : ', '.$this->extraOrderByInfo;
        // Added tick marks using implod/explode in case custom order by columns have periods in them.
		$query = $this->selectClause.' '.$this->joinClause.' '.$this->whereClause.' order by '.$this->addTickMarks($this->orderByColumn).' '.$this->getAscOrDesc().' '.$this->extraOrderByInfo.' limit '.$this->start.', '.$this->displayNum;
        if($this->viewQuery)
		{
			$this->information[] = '<div id="mateViewQuery" align="left">'.nl2br($query).'</div>';
		}
		$result = $this->doQuery($query);
		if(mysql_num_rows($result) > 0)
		{
			$html .= '<div><form id="'.$this->varPrefix.'_table_form" style="margin: 0px;"><table '.$this->tableInfo.'>
			<tr class="header">';
			if(stristr($this->permissions,'M'))
			{
				$html .= '<td id="select_all_cb_cell" width="40" align="left"><input type="checkbox" id="select_all_cb" onclick="selectCbs(this,\''.$this->varPrefix.'\');" /></td>';
			}
			
			if(stristr($this->permissions,'I') && $this->iconColPosition == 'first')
			{
				$html .= strlen($this->iconTitle) > 0 ? '<td>'.$this->iconTitle.'</td>' : '<td>&nbsp;</td>';
			}
			foreach($this->tableColumns as $col => $info)
			{
				if(stristr($info['perms'],'T'))
				{
					$colHeaderInfo = isset($info['col_header_info']) ? $info['col_header_info'] : '';
					if($this->orderByColumn == $col)
					{
						list($oppAscOrDesc,$arrow) = $this->ascOrDesc == 'asc' ? array('desc','&uarr;') : array('asc','&darr;');
						$html .= '<td '.$colHeaderInfo.' ><a href="javascript: toAjaxTableEditor(\'order_by_changed\', new Array(\''.$col.'\',\''.$oppAscOrDesc.'\'));">'.$info['display_text'].'</a> '.$arrow.'</td>';
					}
					else
					{
						$html .= '<td '.$colHeaderInfo.' ><a href="javascript: toAjaxTableEditor(\'order_by_changed\', new Array(\''.$col.'\',\'asc\'));">'.$info['display_text'].'</a></td>';			
					}
				}
			}
			foreach($this->userColumns as $column)
			{
				$html .= isset($column['title']) ? '<td>'.$column['title'].'</td>' : '<td>&nbsp;</td>';
			}
			if(stristr($this->permissions,'I') && $this->iconColPosition == 'last')
			{
				$html .= strlen($this->iconTitle) > 0 ? '<td>'.$this->iconTitle.'</td>' : '<td>&nbsp;</td>';
			}
			$html .= '</tr>';
			$bgColor = $this->evenRowColor;
			while($row = mysql_fetch_assoc($result))
			{
				$numRows++;
				$bgColor = $bgColor == $this->oddRowColor ? $this->evenRowColor : $this->oddRowColor;
				$cb = '';
				$rowInfo = '';
				if(stristr($this->permissions,'M'))
				{
					if(is_callable($this->disableMultCbFun) && call_user_func($this->disableMultCbFun,$row))
					{
						//$cb = '<td>&nbsp;</td>';
						$cb = '<td><input class="rowCheckBox" type="checkbox" id="cb_'.$numRows.'" value="'.$row[$this->primaryKeyCol].'" disabled="disabled" /></td>';
					}
					else
					{
						$rowInfo = 'onclick="cellClicked(\''.$numRows.'\');" style="cursor: pointer;"';
						$cb = '<td><input class="rowCheckBox" type="checkbox" id="cb_'.$numRows.'" onclick="checkBoxClicked(this)" value="'.$row[$this->primaryKeyCol].'" /></td>';						
					}
				}
				$extraRowInfo = strlen($this->extraRowInfo) > 0 ? str_replace(array($this->replaceWithId,'#rowNum#'),array($row[$this->primaryKeyCol],$numRows),$this->extraRowInfo) : '';
				$html .= '<tr bgcolor="'.$bgColor.'" id="row_'.$numRows.'" '.$rowInfo.' '.$extraRowInfo.' class="ajaxRow">'.$cb;
				if(stristr($this->permissions,'I') && $this->iconColPosition == 'first')
				{
					$html .= $this->formatIcons($row[$this->primaryKeyCol],$row);
				}				
				foreach($this->tableColumns as $col => $info)
				{
					if(stristr($info['perms'],'T'))
					{
						$value = $row[$col];
                        $tableCellInfo = isset($info['table_cell_info']) ? $info['table_cell_info'] : '';
						if(isset($info['table_fun']) && is_callable($info['table_fun']))
						{
							$value = call_user_func($info['table_fun'],$col,$value,$row);
						}
						$value = strlen(trim($value)) > 0 ? $value : '&nbsp;';
						if(isset($info['sub_str']) && strlen($value) > $info['sub_str'])
						{
							$value = substr($value,0,$info['sub_str']).'...';
						}
						if($this->searchString && $this->useHighlight && $value != '&nbsp;')
						{
							$value = $this->highlightSearchString($this->searchString,$value);
						}
						$html .= '<td '.$tableCellInfo.'>'.$value.'</td>';
					}
				}
				foreach($this->userColumns as $column)
				{
					if(isset($column['call_back_fun']) && is_callable($column['call_back_fun']))
					{
						$html .= call_user_func($column['call_back_fun'],$row);
					}
				}
				if(stristr($this->permissions,'I') && $this->iconColPosition == 'last')
				{
					$html .= $this->formatIcons($row[$this->primaryKeyCol],$row);
				}
				$html .= '</tr>';
			}
			$html .= '</table></form></div>';
		}
		else
		{
			$html = '<b>'.$this->langVars->ttlNoRecord.'</b>';
		}
		$this->retArr[] = array('layer_id' => 'tableLayer', 'where' => 'innerHTML', 'value' => $html);

		if($this->recordInfo)
		{
			if($this->numResults > 0)
			{
				$end = $this->displayNum + $this->start;
				$end = $end < $this->numResults ? $end : $this->numResults;
				$start = $this->start + 1;
				$recordHtml = '<div>'.sprintf($this->langVars->ttlDispRecs,$start,$end,number_format($this->numResults)).'</div>';
			}
			else
			{
				$recordHtml = '<div>'.$this->langVars->ttlDispNoRecs.'</div>';
			}
			$this->retArr[] = array('layer_id' => 'recordLayer', 'where' => 'innerHTML', 'value' => $recordHtml);
		}
		
		if(is_callable($this->tableScreenFun))
		{
			call_user_func($this->tableScreenFun);
		}
	}

	function getDispNumDropDown()
	{
		$value = $this->displayNumInc;
		$html = '<select id="display_number" onchange="toAjaxTableEditor(\'display_num_changed\',this.value);">';
		while($value < $this->numResults && $value < $this->maxDispNum)
		{
			$value = $value + $this->displayNumInc;
			if($value == $this->displayNum)
			{
				$html .= '<option value="'.$value.'" selected="selected">'.$value.'</option>';
			}
			else
			{
				$html .= '<option value="'.$value.'">'.$value.'</option>';
			}
		}
		$html .= '</select>';
		return $html;
	}

	function getPageDropDown()
	{
        $pages = array();
        $curPage = round($this->start / $this->displayNum);
        $numPages = ceil($this->numResults / $this->displayNum);
        if($numPages == 0)
		{
			$pages = array(0);
		}
		else if($numPages < $this->showAll) 
        {
            $pages = range(1, $numPages);
        } 
        else 
        {
            for($i = 1; $i <= $this->pageBegin; $i++)
            {
                $pages[] = $i;
            }
            for($i = $numPages - $this->pageEnd; $i <= $numPages; $i++)
            {
                $pages[] = $i;
            }
            $i = $this->pageBegin;
            $x = $numPages - $this->pageEnd;
            $metBoundary = false;
            while($i <= $x)
            {
                if($i >= ($curPage - $this->pageRange) && $i <= ($curPage + $this->pageRange)) 
                {
                    $i++;
                    $metBoundary = true;
                } 
                else 
                {
                    $i = $i + floor($numPages / $this->pagePercent);
    
                    if ($i > ($curPage - $this->pageRange) && !$metBoundary)
                    {
                        $i = $curPage - $this->pageRange;
                    }
                }
                if ($i > 0 && $i <= $x) 
                {
                    $pages[] = $i;
                }
            }
            sort($pages);
            $pages = array_unique($pages);
        }

        $html = '<select id="page_number" onchange="toAjaxTableEditor(\'page_num_changed\',this.value);">';
        foreach($pages as $i)
        {
			$value = ($i - 1) * $this->displayNum;
			$value = $value < 0 ? 0 : $value;
            if(($i - 1) == $curPage)
			{
				$html .= '<option value="'.$value.'" selected="selected" style="font-weight: bold">'.$i.'</option>';
			}
			else
			{
				$html .= '<option value="'.$value.'">'.$i.'</option>';
			}
		}
		$html .= '</select>';
		return $html;
	}

	function getAdvancedSearchHtml()
	{
		//<p align="center">When searching with dates use yyyy-mm-dd format.</p>';
		$html = '<div id="searchLayer" >';
		for($i = 0; $i < $this->numAdvSearches; $i++)
		{
			$html .= '<p><select id="as_cols_'.$i.'"><option value="">'.$this->langVars->lblSelect.'</option>';
			foreach ($this->tableColumns as $col => $info)
			{
				if(stristr($info['perms'],'S'))
				{
					if(isset($this->advSearches[$i]['cols']) && $this->advSearches[$i]['cols'] == $col)
						$html .= '<option value="'.$col.'" selected>'.$info['display_text'].'</option>';
					else
						$html .= '<option value="'.$col.'">'.$info['display_text'].'</option>';
				}
			}
			$html .= '</select>&nbsp;<select id="as_opts_'.$i.'">';
			foreach ($this->opts as $sign => $text)
			{
				if(isset($this->advSearches[$i]['opts']) && $this->advSearches[$i]['opts'] == $sign)
					$html .= '<option value="'.$sign.'" selected>'.$text.'</option>';
				else
					$html .= '<option value="'.$sign.'">'.$text.'</option>';
			}
			$html .= '</select>&nbsp;';
			if(isset($this->advSearches[$i]['strs']))
				$html .= '<input type="text" id="as_strs_'.$i.'" size="28" value="'.$this->advSearches[$i]['strs'].'" onKeyPress="if(enterPressed(event)){handleAdvancedSearch(\''.$this->numAdvSearches.'\'); return false;}" /></p>';
			else
				$html .= '<input type="text" id="as_strs_'.$i.'" size="28" value="" onKeyPress="if(enterPressed(event)){handleAdvancedSearch(\''.$this->numAdvSearches.'\'); return false;}" /></p>';			
		}
		$allChecked = $this->matchAll ? 'checked="checked"' : '';
		$anyChecked = $this->matchAll ? '' : 'checked="checked"';
		$html .= '
		<p align="center">
		<p>'.$this->langVars->lblMatch.'
		<input type="radio" name="match" value="all" id="match_all" '.$allChecked.' onclick="toAjaxTableEditor(\'match_all\',\'\');"> <label for="match_all">'.$this->langVars->lblAllCrit.'</label>
		<input type="radio" name="match" value="any" id="match_any" '.$anyChecked.' onclick="toAjaxTableEditor(\'match_any\',\'\');"> <label for="match_any">'.$this->langVars->lblAnyCrit.'</label></p>
		<button onclick="handleAdvancedSearch(\''.$this->numAdvSearches.'\');">'.$this->langVars->lblSearch.'</button>&nbsp;<button onclick="toAjaxTableEditor(\'clear_adv_search\',\'\');">'.$this->langVars->btnCSearch.'</button>&nbsp;<button onclick="toAjaxTableEditor(\'add_adv_search\',\'\');">'.$this->langVars->btnAddCrit.'</button>
		</p>
		</div>';
		return $html;
	}

	function highlightSearchString($needle,$haystack)
	{
		if(!empty($needle))
		{
			$highlight = '<span style="'.$this->highlightHash.'">\1</span>';
			//$pattern = '#(%s)#i';
			$pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#i';
			$regex = sprintf($pattern, preg_quote($needle));
			return preg_replace($regex,$highlight,$haystack);
		}
		else
		{
			return $haystack;
		}
	}
	
	function getSelect($query,$colName,$defaultValue = '', $otherInfo = '')
	{
		$html = '<select id="'.$colName.'" name="'.$colName.'" '.$otherInfo.'><option value="" selected="selected">'.$this->langVars->lblSelect.'</option>';
		$result = $this->doQuery($query);
		while($row = mysql_fetch_array($result,MYSQL_NUM))
		{
			if($row[0] == $defaultValue)
				$html .= '<option value="'.$row[0].'" selected="selected">'.$row[1].'</option>';
			else
				$html .= '<option value="'.$row[0].'">'.$row[1].'</option>';
		}
		$html .= '</select>';		
		return $html;
	}

	function getSelectFromArray($selArr,$nameAndId,$defaultValue = '', $otherInfo = '')
	{
		$html = '<select id="'.$nameAndId.'" name="'.$nameAndId.'" '.$otherInfo.'><option value="" selected="selected">'.$this->langVars->lblSelect.'</option>';

		foreach($selArr as $value => $display)
		{
			if($value == $defaultValue)
				$html .= '<option value="'.$value.'" selected="selected">'.$display.'</option>';
			else
				$html .= '<option value="'.$value.'">'.$display.'</option>';
		}
		$html .= '</select>';		
		return $html;
	}
	
    function addTickMarks($string)
    {
        return '`'.implode('`.`',explode('.',$string)).'`';
    }
    
    /* Thanks to Otto Ebeling and Max Technologies Ltd for helping with the security functions. */
    function getAscOrDesc()
    {
       if ($this->ascOrDesc == 'desc')
       {
           return 'desc';
       }
       else
       {
           return 'asc';
       }
    }
    
    function hasRightsToRow($id)
    {
        $originalColumns = $this->tableColumns;
        // Unset the hidden columns here because if there are joins there can be more or less rows returned.
        $this->unsetHiddenColumns();
        $this->formatJoinClause();
        $this->formatSelectClause();
        $this->formatWhereClause();
        $whereClause = strlen($this->whereClause) > 0 ? "and ".$this->tableName.'.'.$this->primaryKeyCol." = '$id'" : "where ".$this->tableName.'.'.$this->primaryKeyCol." = '$id'";
        $query = $this->selectClause.' '.$this->joinClause.' '.$this->whereClause.' '.$whereClause;
        $this->selectClause = '';
        $this->whereClause = '';
        $this->joinClause = '';
        $this->tableColumns = $originalColumns;
        $result = $this->doQuery($query);
        if($row = mysql_fetch_assoc($result))
        {
            return true;
        }
        else
        {
            return false;
        }
    }
	
    function hasRightsToRows($idArr)
    {
		$originalColumns = $this->tableColumns;
		// Unset the hidden columns here because if there are joins there can be more or less rows returned.
		$this->unsetHiddenColumns();
		$this->formatJoinClause();
		$this->formatSelectClause();
		$this->formatWhereClause();
		$whereClause = strlen($this->whereClause) > 0 ? "and ".$this->tableName.'.'.$this->primaryKeyCol." in ('".implode("','",$idArr)."')" : "where ".$this->tableName.'.'.$this->primaryKeyCol." in ('".implode("','",$idArr)."')";
		$query = $this->selectClause.' '.$this->joinClause.' '.$this->whereClause.' '.$whereClause;
		$this->selectClause = '';
		$this->whereClause = '';
		$this->joinClause = '';
		$this->tableColumns = $originalColumns;
		$result = $this->doQuery($query);
		if(mysql_num_rows($result) == count($idArr))
		{
			return true;
		}
		else
		{
			return false;
		}
    }
    
    function handleHacking()
    {
        if(isset($this->handleHackingFun) && is_callable($this->handleHackingFun))
        {
            call_user_func($this->handleHackingFun);
        }
        else
        {
            exit();
        }
    }
    /* End security functions */
    
	function setLangVars()
	{
		if(class_exists('LangVars'))
		{
			$this->langVars = new LangVars();
		}
		else
		{
			$langVarsPath = str_replace('AjaxTableEditor.php','lang/LangVars-en.php',__file__);
			if(is_file($langVarsPath))
			{
				require_once($langVarsPath);
				$this->langVars = new LangVars();
			}
			else
			{
				$this->warnings[] = 'The language file could not be found.';
			}
		}
	}
	
	function AjaxTableEditor($tableName,$primaryCol,$errorFun,$permissions,$tableColumns)
	{
		$this->setLangVars();
		$this->tableName = $tableName;
		$this->primaryKeyCol = $primaryCol;
		$this->errorFun = $errorFun;
		$this->permissions = $permissions;
		$this->tableColumns = $tableColumns;
		$this->setDefaults();
	}
					
}
?>
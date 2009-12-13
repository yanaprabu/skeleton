<?php
#include_once 'A/DL.php';
#include_once 'A/Template/Strreplace.php';
#include_once 'A/Controller/Form.php';
#include_once 'A/Controller/Form/Field.php';
#include_once 'A/Filter/Regexp.php';

/**
 * 
 * 
 * @package Misc 
 */
class EditController extends A_Controller_Form {
	protected $template;
	protected $template_file = '';
	protected $template_text = '';
	protected $template_block_layout = '';
	protected $base_url = '';
	protected $redirect_url = '';
	protected $fields = array();
	protected $hidden = '';
	protected $db = null;
	protected $table = '';
	protected $table_key = '';
	protected $errmsg = '';
	
	public function __construct($db) {
		$this->db = $db;

		$this->formfield = new A_Controller_Form_Field();
		$this->formfield->setDB($db);
		
		$this->template = new A_Template_Strreplace();
		
		$handlers = array(
			'init' => new A_DLInstance($this, 'init'), 
			'submit' => new A_DLInstance($this, 'submit'), 
			'done' => new A_DLInstance($this, 'done')
			);
		parent::__construct($handlers);
	}
	
	public function setTemplate($file, $block_layout='') {
		$this->template_file = $file;
		if ($block_layout) $this->template_block_layout = $block_layout;
		return $this;
	}

	public function setTemplateText($text) {
		$this->template_text = $text;
		return $this;
	}

	public function setRedirectURL($url) {
		$this->redirect_url = $url;
		return $this;
	}

	public function setBaseURL($url) {
		$this->base_url = $url;
		return $this;
	}

	public function setTable($table, $table_key) {
		$this->table = $table;
		$this->table_key = $table_key;
		return $this;
	}

	public function setFields($fields) {
		$this->fields = $fields;
		return $this;
	}

	public function run($locator) {
		$request = $locator->get('Request');

		$this->id = $request->get($this->table_key, '/[^a-zA-Z0-9]/');

		parent::run($locator);
	}

	public function processFieldStrings() {
		foreach ($this->fields as $field) {
			$rawparams = explode(';', $field);
			$params = array();
			$filter = null;
			$rules = array();
			foreach ($rawparams as $param) {
				if(strstr ($param, '=') !== false) {
					list($type, $values) = explode('=', $param);
					$type = trim($type);
#echo "$type, $values<br/>";
					switch ($type) {
					case 'filter':
						$filter = $values;
						break;
					case 'rule':
						$args = explode(',', $values);
						$name = array_shift($args);
						$rules[$name] = $args;
						break;
					default:
						if (! isset($values)) {
							$values = '';
						}
						$params[$type] = $values;
						break;
					}
				}
			}
			if (isset($params['name'])) {
				$n = $params['name'];
				$formfield[$n] = new A_Controller_FormParameter($params['name']);
				// add HTML form field parameters for use later
				if (isset($params['value'])) {
					$formfield[$n]->setValue($params['value']);
				}
				if (isset($params['type'])) {
					$formfield[$n]->setType($params);
				}
				if ($rules) {
					foreach ($rules as $name => $args) {
						$name = trim($name);
						if ($name) {
							$class = 'A_Rule_' . $name;
							if (! class_exists($class)) {
								$filename = "A/Rule/$name.php";
								if (file_exists($filename)) {
									include $filename;
								}
							}
							if (class_exists($class)) {
								$formfield[$n]->addRule(new $class($args[0], isset($args[1])?$args[1]:null, isset($args[2])?$args[2]:null), $params['name']);
							}
						}
					}
				}
				if ($filter) {
					$formfield[$n]->addFilter(new A_Filter_Regexp("/[^$filter]/", ''));
				}
				$this->addParameter($formfield[$n]);
			}
		}

	}

	public function fieldToHTML($attr, $value='') {
		if (is_array($attr) && isset($attr['type']) && isset($attr['name'])) {
#echo "type={$attr['type']}<br/>";
			if (($attr['type'] == 'select') || ($attr['type'] == 'radio') || ($attr['type'] == 'checkbox')) {
				if (isset($attr['values']) && is_string($attr['values'])) {
					$attr['values'] = explode('|', $attr['values']);
				}
				if (isset($attr['labels']) && is_string($attr['labels'])) {
					$attr['labels'] = explode('|', $attr['labels']);
				}
			}
			return $this->formfield->toHTML($attr, $value);
		} else {
			return $value;
		}
	}
	
	public function init($locator) {
		$config = $locator->get('Config');
		$row = array();
		if ($this->id) {
			$result = $this->db->query("SELECT * FROM {$this->table} WHERE {$this->table_key}='{$this->id}'");
			if ($this->db->isError()) {
				$this->errmsg = $this->db->getMessage();
			} else {
				$row = $result->fetchRow();
			}
		} else {
			$row[$this->table_key] = '';
		}
		foreach (array_keys($this->params) as $name) {
			$field = $this->getParameter($name);
			if (is_array($field->type)) {
				if (! isset($field->type['name'])) {
					$field->type['name'] = $name;
				}
#				if (! isset($field->type['value'])) {
#					$field->type['value'] = $field->value;
#				}
			}
			$html = $this->fieldToHTML($field->type, isset($row[$name])?$row[$name]:$field->value);
			$this->template->set($field->name, $html);
		}

#		$this->view = $locator->get('view_main');
#		$this->view->setContent($this->template->render('signup'));
	}
	
	public function submit($locator) {
#echo "submit<br/>";
		$config = $locator->get('Config');

		$errmsgs = $this->getErrorMsgs();
		foreach ($errmsgs as $key => $val) {
			if (! $val) {
				unset($errmsgs[$key]);
			}
		}
		$this->errmsg = implode(', ', $errmsgs);
		foreach (array_keys($this->params) as $name) {
			$field = $this->getParameter($name);
			$html = $this->fieldToHTML($field->type, $field->value);
			$this->template->set($field->name, $html);
		}

#		$this->view->setContent($this->template->render('signup'));
	}
	
	public function getEscapedData() {
		$data = array();
		foreach (array_keys($this->params) as $name) {
			$field = $this->getParameter($name);
			if (is_array($field->value)) {
				$field->value = implode('|', $field->value);
			}
			$data[$name] = $this->db->escape($field->value);
		}
		return $data;
	}
	
	public function done($locator) {
#echo "done<br/>";
#		$session = $locator->get('Session');

		$data = $this->getEscapedData();
		if ($this->id) {
			foreach ($data as $name => $value) {
				$sets[] = "$name='$value'";
			}
			$sql = "UPDATE {$this->table} SET " . implode(',', $sets) . " WHERE {$this->table_key}='{$this->id}'";
			$param = '?page=resume';
		} else {
			$sql = "INSERT INTO {$this->table} (" . implode(',', array_keys($data)) . ") VALUES ('" . implode("','", $data) . "')";
			$param = '?last_row=recalc';
		}
		$this->db->query($sql);
		if ($this->db->isError()) {
			$this->errmsg = $this->db->getMessage();
		} else {
#			$session->set($this->session_var, $data);
#			$session->close();
		
			$response = $locator->get('Response');
			$response->setRedirect($this->redirect_url . $param);
		}

	}

	public function render($locator) {
#echo "render<br/>";
#		$config = $locator->get('Config');
#		$request = $locator->get('Request');

/*		
		$id = $request->get('id', '/[^0-9]/');

		$row = '';
		$content = '';

		$template = new A_Template_Strreplace($this->template_file);
		$template->makeBlocks();
		
		if ($id) {
			$result = $this->db->query('SELECT ' . implode(',', array_keys($this->fields)) . " FROM {$this->table} WHERE {$this->table_key}='$id'");
			if ($this->db->isError()) {
				$this->errmsg = $this->db->getMessage();
#echo("{$this->errmsg}<br/>");
			} else {
				$row = $result->fetchRow();
dump($row, 'ROW: ');
			}
		}

		if ($row) {
			foreach ($row as $field => $value) {
				$template->set($field, $value);
			}
		} else {
			$template->set('pagerlinks', '');
		}
		$template->set('errmsg', $this->errmsg);
		return $template->render($this->template_block_layout);
*/
		if ($this->template_file) {
			$this->template->setFilename($this->template_file);
		} else {
			$this->template->setTemplate($this->template_text);
		}
		$this->template->makeBlocks();
		$this->template->set('errmsg', $this->errmsg ? 'Errors: '.$this->errmsg : '');
		$this->template->set('hidden', $this->hidden);
		$this->template->set('action', $this->base_url);

		return $this->template->render($this->template_block_layout);
	}

}

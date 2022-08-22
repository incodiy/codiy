<?php
namespace Incodiy\Codiy\Controllers\Admin\Modules;

use Incodiy\Codiy\Controllers\Core\Controller;
use Incodiy\Codiy\Models\Admin\Modules\Form;

/**
 * Created on 23 Mar 2021
 * Time Created	: 17:35:59
 *
 * @filesource	FormController.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
 
class FormController extends Controller {
	
//	private $route_group		= 'modules';

//	protected $inputFiles		= ['file_field', 'file_field_alt'];
//	protected $hideFields		= ['text_field', 'selectbox_field'];
	protected $excludeFields	= ['password_field'];
	
	private $setTableFields		= ['email_field:Email', 'text_field', 'number_field:Number', 'month_field:Month', 'time_field', 'file_field', 'file_field_alt', 'updated_at'];
	
	public function __construct() {
		parent::__construct();
		
		$this->model(Form::class);
		
		$this->preventInsertDbThumbnail('file_field_alt');
	//	$this->setImageElements('file_field', 1, true);
	//	$this->setFileElements('file_field_alt', 'file', 'txt,xlx,xlxs,pdf', 2);
	}
	
	public function indexz() {
		$this->meta->title('Form Object');
		
	//	$this->table->mergeColumns('Text Merged Column', ['text_field', 'email_field']);
	//	$this->table->setCenterColumns(['month_field'], true, true);
	//	$this->table->setRightColumns(['number_field', 'formula_f', 'formula_f1'], true, true);
	//	$this->table->setBackgroundColor('#5D94F0', 'yellow', ['file_field_alt', 'email_field']);
		
		$this->table->searchable();
		$this->table->clickable();
		$this->table->sortable();
		
		$this->table->columnCondition('text_field', 'row', '!==', 'Testing', 'background-color', '#F1F7CB');
		$this->table->columnCondition('email_field', 'row', '==', 'test@mail.com', 'background-color', '#FFC107');
		$this->table->columnCondition('email_field', 'cell', '==', 'testing@mail.com', 'background-color', '#CDE3A2');
		$this->table->columnCondition('email_field', 'cell', '!=', 'testing@mail.com', 'background-color', '#E2F6BB');
		$this->table->columnCondition('email_field', 'cell', '==', 'test@mail.com', 'replace', 'mail@replace.ment');
		$this->table->columnCondition('email_field', 'cell', '!=', 'test@mail.com', 'replace', 'replace@mail.com');
		$this->table->columnCondition('text_field', 'cell', '==', 'Testing', 'color', '#28A745');
		$this->table->columnCondition('text_field', 'cell', '!=', 'Testing', 'color', '#007BFF');
		$this->table->columnCondition('text_field', 'cell', '==', 'Testing', 'prefix', '# ');
		$this->table->columnCondition('text_field', 'cell', '!==', 'Testing', 'prefix', '! ');
		$this->table->columnCondition('time_field', 'cell', '!==', '19:08:00', 'suffix', ' #');
		$this->table->columnCondition('time_field', 'cell', '==', '19:08:00', 'suffix', ' !');
		$this->table->columnCondition('formula_f1', 'cell', '>', 140, 'background-color', '#F0CDCD');
		$this->table->columnCondition('formula_f1', 'cell', '>', 140, 'replace', 0);
		
		$this->table->formula('formula_f', 'Formula Label', ['number_field', 'month_field'], "cos(number_field+month_field)*100+tan(month_field)");
		$this->table->formula('formula_f1', null, ['number_field', 'month_field'], "(number_field+month_field)*number_field");
		
		$this->table->filterGroups('month_field', 'selectbox', true);
		$this->table->filterGroups('email_field', 'checkbox', false);
		$this->table->filterGroups('text_field', 'radiobox', ['email_field', 'number_field']);
		
		$this->table->lists('test_inputform', $this->setTableFields, true);
		
		return $this->render();
	}
	
	public function index() {
		$this->setPage('Form Object');
		/* 
		$this->table->mergeColumns('Text Merged Column', ['text_field', 'email_field']);
		$this->table->mergeColumns('File Merged Column', ['file_field', 'file_field_alt', 'updated_at']);
		$this->table->setCenterColumns(['text_field'], true, false);
		$this->table->setRightColumns(['time_field'], true, true);
		
		$this->table->setBackgroundColor('#5D94F0', 'yellow', ['file_field_alt', 'email_field']);
		$this->table->setBackgroundColor('#7CA7EE', '#fff', ['time_field']);
		 */
		$this->table->searchable(['text_field', 'email_field', 'updated_at']);
		$this->table->clickable(['text_field', 'email_field']);
		$this->table->sortable(['text_field', 'email_field']);
		
		$this->table->lists('test_inputform', $this->setTableFields);
	/* 	$this->table->clear();
		
		$this->table->mergeColumns('Text Merged Column', ['parent_name', 'module_name']);
		$this->table->searchable();
		$this->table->clickable();
		$this->table->sortable();
		$this->table->lists('base_module', ['parent_name', 'module_name', 'module_info', 'flag_status'], false);
		$this->table->clear(); */
		/* 
	//	$this->table->model($this->model);
		$this->table->mergeColumns('Text Merged Column', ['text_field', 'email_field']);
		$this->table->mergeColumns('Formula Merged Column', ['formula_f', 'formula_f1']);
	//	$this->table->orderby('id', 'desc');
		$this->table->searchable();
		$this->table->clickable();
		$this->table->sortable();
		
		$this->table->columnCondition('text_field', 'row', '!==', 'Testing', 'background-color', '#F1F7CB');
		$this->table->columnCondition('email_field', 'row', '==', 'test@mail.com', 'background-color', '#FFC107');
		$this->table->columnCondition('email_field', 'cell', '==', 'testing@mail.com', 'background-color', '#CDE3A2');
		$this->table->columnCondition('email_field', 'cell', '!=', 'testing@mail.com', 'background-color', '#E2F6BB');
		$this->table->columnCondition('email_field', 'cell', '==', 'test@mail.com', 'replace', 'mail@replace.ment');
		$this->table->columnCondition('email_field', 'cell', '!=', 'test@mail.com', 'replace', 'replace@mail.com');
		$this->table->columnCondition('text_field', 'cell', '==', 'Testing', 'color', '#28A745');
		$this->table->columnCondition('text_field', 'cell', '!=', 'Testing', 'color', '#007BFF');
		$this->table->columnCondition('text_field', 'cell', '==', 'Testing', 'prefix', '# ');
		$this->table->columnCondition('text_field', 'cell', '!==', 'Testing', 'prefix', '! ');
		$this->table->columnCondition('time_field', 'cell', '!==', '19:08:00', 'suffix', ' #');
		$this->table->columnCondition('time_field', 'cell', '==', '19:08:00', 'suffix', ' !');
		
		$this->table->columnCondition('formula_f1', 'cell', '>', 140, 'background-color', '#F0CDCD');
		$this->table->columnCondition('formula_f1', 'cell', '>', 140, 'replace', 0);
		$this->table->formula('formula_f', 'Formula Label', ['number_field', 'month_field'], "cos(number_field+month_field)*100+tan(month_field)");
		$this->table->formula('formula_f1', null, ['number_field', 'month_field'], "(number_field+month_field)*number_field");
		 */
	//	$this->table->where('time_field', '=', '16:11:00');
	//	$this->table->where('text_field', 'like', '%Testing%');
	//	$this->table->where('id', '>', 3);
	//	$this->table->lists('test_inputform', $this->setTableFields, true);
	
		/* 
		$this->table->searchable();
		$this->table->clickable();
		$this->table->sortable();
		$this->table->lists('base_group', [], false);
		
		$this->table->searchable();
		$this->table->clickable();
		$this->table->sortable();
		$this->table->lists('base_postal_code', [], false);
		 */
		/* 
		$this->table->query('select group_name, group_info from base_group where id != 1');
		$this->table->lists(null, ['group_name', 'group_info'], false);
		 */
		return $this->render();
	}
	
	public function create() {
		$this->setPage('Form Object');
		
		$this->form->modelWithFile();
		
		$this->form->text('text_field');
		$this->form->textarea('textarea_field', null, ['class' => 'text-area-class ckeditor', 'maxlength' => 200, 'placeholder' => 'Isi Konten']);
		$this->form->password('password_field', ['class' => 'text-sub2-class']);
		$this->form->email('email_field', null, ['class' => 'text-class']);
		
		$this->form->openTab('Multi Select Form');
		$this->form->selectbox('selectbox_field', ['L' => 'Large', 'S' => 'Small']);
		$this->form->checkbox('checkbox_field', [
			1 => 'Check Satu',
			2 => 'Check Dua',
			3 => 'Check Tiga'
		]);
		$this->form->radiobox('radiobox_field', ['radio 1', 'radio 2', 'radio 3']);
		
		$this->form->openTab('Date And Time');
		$this->form->date('date_field');
		$this->form->datetime('datetime_field');
		$this->form->daterange('daterange_field');
		$this->form->time('time_field');
		$this->form->month('month_field');
		
		$this->form->openTab('Others');
		$this->form->number('number_field');
		
		$this->form->file('file_field', ['imagepreview']);
		$this->form->file('file_field_alt');
		
		$this->form->closeTab();
		
		$this->form->close('Submit', ['class' => 'btn btn-primary btn-slideright pull-right']);
		
		return $this->render();
	}
}
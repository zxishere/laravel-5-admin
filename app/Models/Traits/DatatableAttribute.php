<?php namespace App\Models\Traits;

trait DatatableAttribute {
    public $datatable_addtions =  [
        "checkbox" => "{ data: null,title:'<input type=\"checkbox\" name=\"select_all\"/>',render: function (data){var permisson = (data.action.toLowerCase().indexOf(\"data-id\") >= 0) ? \"\" : \"disabled=disabled\";return '<input type=\"checkbox\" name=\"ids[]\" value=\"' + data.id + '\" '+permisson+' />'},className:'dt-center',width:'25px',orderable: false, searchable: false},",
        "action"   => "{ data: 'action', name: 'action',className:'dt-center',title:'#', orderable: false, searchable: false}",
    ];

    public function config()
    {
        $str = "";
        $array = array_dot($this->model->datatable_fields);
        foreach (array_divide($this->model->datatable_fields)[0] as $value) {
            $str .= "{ data: '".$value."', name: '".$value."', title:'".trans('messages.'.$value)."', orderable: ".strbool($array[$value.'.orderable']).", searchable: ".strbool($array[$value.'.searchable'])." },";
        }
        return $str;
    }

    public function all()
    {
        return $this->model->select(array_divide($this->model->datatable_fields)[0]);
    }

    public function columns(){
        $checkbox_str = '';
        if (auth()->user()->can($this->getRouteName().'_delete')) {
            $checkbox_str =$this->datatable_addtions['checkbox'];
        }
        return "[".$checkbox_str.$this->config().$this->datatable_addtions['action']."]";
    }

    public function action_butttons($model) {
        $buttons =  [];
        if (auth()->user()->can($this->getRouteName().'_view')) {
            $buttons['show'] = '<a class="btn btn-warning btn-xs" href="'.$this->getRouteName().'/'.$model->id.'" data-toggle="tooltip" title="'.trans('messages.show').'" data-original-title="'.trans('messages.show').'"><i class="fa fa-eye"></i></a>';
        }
        if (auth()->user()->can($this->getRouteName().'_update',$model)) {
            $buttons['edit'] = '<a class="btn btn-primary btn-xs" href="'.$this->getRouteName().'/'.$model->id.'/edit" data-toggle="tooltip" title="'.trans('messages.edit').'" data-original-title="'.trans('messages.edit').'"><i class="fa fa-pencil"></i></a>';
        }
        if (auth()->user()->can($this->getRouteName().'_delete',$model)) {
            $buttons['delete'] = '<a class="btn btn-danger btn-xs btn-delete" data-toggle="tooltip"  data-id="'.$model->id.'" title="'.trans('messages.delete').'" data-original-title="'.trans('messages.delete').'"><i class="fa fa-trash"></i></a>';
        }
        return implode("\n",$buttons);
    }
}
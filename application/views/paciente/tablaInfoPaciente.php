<?php 
    if(isset($paciente) && $idPaciente > 0){
        $this->table->set_empty("---");
        $this->table->set_heading(
            'Nombre',
            'Identificación',                
            'Edad',
            'Sexo',
            'Diagnóstico'
        );
        $this->table->add_row(
            array('data' => $paciente->nombre_paciente),
            array('data' => $paciente->identificacion_paciente),          
            array('data' => $paciente->edad_paciente),
            array('data' => $paciente->sexo_paciente),
            array('data' => $paciente->diagnostico_paciente)
        );
        $tmpl = array ( 'table_open'  => '<table class="table table-striped table-bordered table-hover">' );
        $this->table->set_template($tmpl);
        echo $this->table->generate();
    }
?>
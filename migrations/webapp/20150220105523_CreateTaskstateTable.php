<?php

class CreateTaskstateTable extends Ruckusing_Migration_Base
{
    public function up()
    {
	// create task_states table and init default records
	$t = $this->create_table("taskstates");
    	$t->column("state", "string", array('limit' => 32));
    	$t->finish();
        $this->execute("INSERT INTO taskstates values (1,'pending'),(2,'accepted'),(3,'refused'),(4,'interrupted'),(5,'done'),(6,'checked');");

	// add ref col to tasks table and set null records to first state, pending
	$this->add_column("tasks", "id_state", "integer");
	$this->execute("UPDATE tasks SET id_state=1 WHERE id_state IS NULL");

    }//up()

    public function down()
    {
	// drop task_states table
	$this->drop_table("taskstates");

	// remove ref col from tasks table
	$this->remove_column("tasks", "id_state");

    }//down()
}

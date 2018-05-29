<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTasksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('uid');
            $table->unsignedInteger('process_id');

            //task description
            $table->text('title');
            $table->text('description')->nullable();

            //type definitions
            $table->enum('type', ['NORMAL', 'ADHOC', 'SUBPROCESS', 'HIDDEN', 'GATEWAYTOGATEWAY', 'WEBENTRYEVENT', 'END-MESSAGE-EVENT', 'START-MESSAGE-EVENT', 'INTERMEDIATE-THROW-MESSAGE-EVENT', 'INTERMEDIATE-CATCH-MESSAGE-EVENT', 'SCRIPT-TASK', 'START-TIMER-EVENT', 'INTERMEDIATE-CATCH-TIMER-EVENT', 'END-EMAIL-EVENT', 'INTERMEDIATE-THROW-EMAIL-EVENT', 'SERVICE-TASK'])->default('NORMAL');
            $table->enum('assign_type', ['BALANCED', 'MANUAL', 'EVALUATE', 'REPORT_TO', 'SELF_SERVICE', 'STATIC_MI', 'CANCEL_MI', 'MULTIPLE_INSTANCE', 'MULTIPLE_INSTANCE_VALUE_BASED'])->default('BALANCED');
            $table->enum('routing_type', ['NORMAL', 'FAST', 'AUTOMATIC'])->default('NORMAL');

            //variables assignment
            $table->string('priority_variable', 100)->default('');
            $table->string('assign_variable', 100)->default('@@SYS_NEXT_USER_TO_BE_ASSIGNED');
            $table->string('group_variable', 100)->nullable();

            //is it an start task
            $table->boolean('is_start_task')->default(false);

            //template screen showed when routing
            $table->string('routing_screen_template', 128)->nullable();

            //duration json options
            $table->json('timing_control_configuration')->nullable();
            /*************************************************************************************************
            the field timing_control_configuration contains the following

             Field          |   Type    |               values                              |   value default
             __________________________________________________________________________________
             * duration     |   float   |                                                   |   0
             * delay_type   |   enum    |['MINUTES', 'HOURS', 'DAYS']                       |   DAYS
             * temporizer   |   float   |                                                   |   0
             * type_day     |   enum    |['WORK_DAYS', 'CALENDAR_DAYS']                     |   WORK_DAYS
             * time_unit    |   enum    |['MINUTES', 'HOURS', 'DAYS', 'WEEKS', 'MONTHS']    |   DAYS
            *************************************************************************************************/

            //Options to run a trigger when you have a Self service timeout
            $table->unsignedInteger('self_service_trigger_id')->nullable();

            //self service json configuration
            $table->json('self_service_timeout_configuration')->nullable();
            /*************************************************************************************************
            the field self_service_timeout_configuration contains the following

            Field                       |   Type     |               values                             |   value default
            __________________________________________________________________________________
             * self_service_timeout     |   integer |                                                   |   0
             * self_service_time        |   integer |                                                   |   0
             * self_service_time_unit   |   string  |  ['MINUTES', 'HOURS', 'DAYS', 'WEEKS', 'MONTHS']  |   HOURS
             * self_service_execution   |   string  |  ['EVERY_TIME', 'ONCE']                           |   EVERY_TIME
             *************************************************************************************************/

            //title and description customized by the user and showed in the cases list
            $table->text('custom_title')->nullable();
            $table->text('custom_description')->nullable();

            $table->timestamps();

            // setup relationships of the task with processes and other tables
            $table->foreign('process_id')->references('id')->on('processes')->ondelete('cascade');
            // setup relationships of the task with triggers and other tables
            $table->foreign('self_service_trigger_id')->references('id')->on('triggers')->ondelete('cascade');

            /*************************************************************************************************
            These fields are used in the old designer. therefore they are removed
             * def_proc_code
             * auto_root
             *
             * mi_instance_variable
             * mi_complete_variable
             *
             * owner_app
             * stg_uid
             *
             * can_upload
             * view_upload
             * view_additional_documentation
             * can_cancel
             * can_pause
             * can_send_message
             * can_delete_docs
             *
             * mi_instance_variable
             * mi_complete_variable
             *
             *
             * posx
             * posy
             * width
             * height
             * color
             * evn_uid
             * boundary
             *
             * last_assigned
             * user
             * to_last_user
             * assign_location
             * assign_location_adhoc
             *
             * Need the definition
             *
             * mobile
             * transfer_fly  //Custom timing control configuration
             *
             ***********************************************************************************************/

            /***********************************************************************************************
             * Information of notifications moved to table task_notifications
             ************************************************************************************************/
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
}

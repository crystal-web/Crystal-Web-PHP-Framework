function replaceAll(find, replace, str) {
    return str.replace(new RegExp(find, 'g'), replace);
}

var Track = function (c,r,y,s,t,a,l, w,e,b) {

    /**
     * @author devphp
     * @date 03-03-2014
     */
    function statusToggle(){
        y(r).on('click', '.checkbox input', function(event){
            var $$ = y(this);
            var taskGroupId = y(this).parents('.task-group').attr('data-task-group');
            var taskId = y(this).val();
            if (t) {
                s.log('statusToggle', 'taskGroupId', taskGroupId);
                s.log('statusToggle', 'taskId', taskId);
            }
            y.ajax({
                method: 'post',
                data: {
                    action: 'statusToggle',
                    taskGroupId: taskGroupId,
                    taskId: taskId
                },
                success: function(data){
                    if (t) {
                        s.log('statusToggle', 'ajax', data);
                    }
                    if (data == 'false') {
                        if (y(this).is(':checked')) {
                            y(this).attr('checked');
                        } else {
                            y(this).removeAttr('checked');
                        }
                        return;
                    }
                    // Work
                    // y('[data-task-group=' + taskGroupId + '] .completed').html(data);
                    $$.parents('.task-group').find('.completed').html(data + "% complet&eacute;");
                }
            });
        });
    }

    /**
     * @author devphp
     * @date 03-03-2014
     */
    function timerToggle(){
        y(r).on('click', '.timer .userTime', function(event){
            var $$ = y(this).parent();
            var taskGroupId = $$.parents('.task-group').attr('data-task-group');
            var taskId = $$.parent().attr('data-task-id');
            if (t) {
                s.log('timerToggle', 'obj', jQuery(event));
                s.log('timerToggle', 'taskGroupId', taskGroupId);
                s.log('timerToggle', 'taskId', taskId);
            }

            if (taskId != undefined){
                y.ajax({
                    method: 'post',
                    data: {
                        action: 'timerToggle',
                        taskId: taskId,
                        taskGroupId: taskGroupId
                    },
                    success: function(data){
                        if (t) {
                            s.log('timerToggle', 'ajax', data);
                        }
                        if (data == 'false') { return; }

                        try {
                            data = JSON.parse(data);
                            s.log(data);
                        } catch (error){
                            bootbox.alert('Qui a tu&eacute; Jim ?');
                            return;
                        }//*/

                        y('.timer .label-warning').removeClass('label-warning').addClass('label-info');
                        if ($$.find('.fa.fa-refresh').is(':visible')){
                            y('.fa.fa-refresh').hide();

                        } else {
                            y('.fa.fa-refresh').hide();
                            $$.find('.fa.fa-refresh').show();
                            $$.find('.userTime').removeClass('label-info').addClass('label-warning');
                        }
                        $$.find('.userTime .time').html(data.time);
                        $$.find('.teamTime .time').html(data.team);
                    }
                });
            }
        });
    }

    /**
     * @author devphp
     * @date 03-03-2014
     */
    function addTask(){
        y(r).on('submit', '[data-task-type]', function(event){
            event.preventDefault();
            var $$ = y(this);

            var data;
            // task type
            var task = y(this).attr('data-task-type');
            if (task == 'newgoals') {
                data = 'goals=' + y(this).find('[name="goals"]').val();
                y(this).find('[name="goals"]').val("");
            } else {
                var goalsid = y(this).find('[name="goalsid"]').val();
                data = 'task=' + y(this).find('[name="task"]').val() + '&goalsid=' + goalsid;
                y(this).find('[name="task"]').val("");
            }

            y.ajax({
                method: "post",
                data: data,
                success: function(data){
                    try {
                        data = JSON.parse(data);
                        s.log(data);
                    } catch (error){
                        bootbox.alert('Qui a tu&eacute; Jim ?');
                        return;
                    }

                    if (task == 'newgoals') {
                        var newtask = y('#newgoals').html();
                        newtask = replaceAll('{task}', data.task, newtask);
                        newtask = replaceAll('{id}', data.id, newtask);
                        y('#accordion .panel').last().after(newtask);
                    } else if (task == 'newtask'){
                        var newtask = y('#newtask').html();
                        newtask = replaceAll('{task}', data.task, newtask);
                        newtask = replaceAll('{clock}', data.id, newtask);
                        y('#collapse-' + goalsid + ' ul').append(newtask);
                    }
                }
            });
        });
    }

    /**
     * @author devphp
     * @date 03-03-2014
     */
    function editTask(){
        y(r).on('click', '.taskEdit', function(event){
            var $$ = y(this).parent();
            var taskGroupId = $$.parents('.task-group').attr('data-task-group');
            var taskId = $$.parent().attr('data-task-id');
            var li = $$.parent('li');
            if (t) {
                s.log('editTask', 'obj', li.find('.checkbox span').text());
                s.log('editTask', 'taskGroupId', taskGroupId);
                s.log('editTask', 'taskId', taskId);
            }
            bootbox.prompt("Renomer la t&acirc;che", function(result) {
                if (result === null) {
                    s.log("Prompt dismissed");
                } else if (result.length > 0) {
                    y.ajax({
                        method: 'post',
                        data: {
                            action: 'editTask',
                            taskId: taskId,
                            taskGroupId: taskGroupId,
                            task: result
                        },
                        success: function(data){
                            if (t) {
                                s.log('editTask', 'ajax', data);
                            }
                            if (data == 'false') { return; }
                        }
                    });
                    li.find('.checkbox span').text(result);
                }
            });
        });
    }

    /**
     * @author devphp
     * @date 03-03-2014
     */
    function delTask(){
        y(r).on('click', '.taskDel', function(){
            var $$ = y(this).parent();
            var taskGroupId = $$.parents('.task-group').attr('data-task-group');
            var taskId = $$.parent().attr('data-task-id');
            var li = $$.parent('li');
            if (t) {
                s.log('delTask', 'obj', li.find('.checkbox span').text());
                s.log('delTask', 'taskGroupId', taskGroupId);
                s.log('delTask', 'taskId', taskId);
            }

            bootbox.confirm("Souhaitez-vous supprimer la t&acirc;che ?", function(result) {
                if (result) {
                    y.ajax({
                        method: 'post',
                        data: {
                            action: 'delTask',
                            taskId: taskId,
                            taskGroupId: taskGroupId
                        },
                        success: function(data){
                            if (t) {
                                s.log('delTask', 'ajax', data);
                            }
                            if (data == 'false') { return; }

                            li.slideUp('slow', function(){
                                li.remove();
                            });
                        }
                    });
                }
            });
        });
    }

    function editTaskGoals(){
        y(r).on('click', '.taskEditGoals', function(event){
            var $$ = y(this).parent();
            var taskGoalsId = $$.parents('[data-task-group]').attr('data-task-group');
            if (t) {
                s.log('editTaskGoals', 'taskGoalsId', taskGoalsId);
                s.log($$.parents('[data-task-group]').find('a').text());
            }

            bootbox.prompt("Renomer la t&acirc;che", function(result) {
                if (result === null) {
                    s.log("Prompt dismissed");
                } else if (result.length > 0) {
                    y.ajax({
                        method: 'post',
                        data: {
                            action: 'editTaskGoals',
                            taskGoalsId: taskGoalsId,
                            task: result
                        },
                        success: function(data){
                            if (t) {
                                s.log('editTaskGoalsk', 'ajax', data);
                            }
                            if (data == 'false') { return; }
                            $$.parents('[data-task-group]').find('a').text(result);
                        }
                    });
                }
            });

        });
    }

    function delTaskGoals(){
        y(r).on('click', '.taskDelGoals', function(event){
            var $$ = y(this).parent();
            var taskGoalsId = $$.parents('[data-task-group]').attr('data-task-group');
            if (t) {
                s.log('taskDelGoals', 'taskGoalsId', taskGoalsId);
            }

            bootbox.confirm("Souhaitez-vous supprimer la t&acirc;che ?", function(result) {
                if (result) {
                    y.ajax({
                        method: 'post',
                        data: {
                            action: 'delTaskGoals',
                            taskGoalsId: taskGoalsId
                        },
                        success: function(data){
                            if (t) {
                                s.log('taskDelGoals', 'ajax', data);
                            }
                            if (data == 'false') { return; }

                            $$.parents('[data-task-group]').slideUp('slow', function(){
                                $$.parents('[data-task-group]').remove();
                            });
                        }
                    });
                }
            });

        });
    }

    return {
        tasks: function() {
            statusToggle();
            timerToggle();
            addTask();
            editTask();
            delTask();
            // addTaskGoals();
            editTaskGoals();
            delTaskGoals();
        }
    }

}(window, document, jQuery, console, true);

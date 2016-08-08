SELECT @compId := id FROM `civicrm_component` where `name` like 'CiviTask';

UPDATE `civicrm_option_value` SET component_id = @compId WHERE name IN
  (
    'Open Case',
    'Follow up',
    'Change Case Type',
    'Change Case Status',
    'Change Case Start Date',
    'Link Cases',
    'Schedule joining date',
    'Issue appointment letter',
    'Fill Employee Details Form',
    'Submission of ID/Residence proofs and photos',
    'Program and work induction by program supervisor',
    'Enter employee data in CiviHR',
    'Group Orientation to organization, values, policies',
    'Start Probation workflow',
    'Conduct appraisal',
    'Collection of appraisal paperwork',
    'Issue confirmation/warning letter',
    'Schedule Exit Interview',
    'Get "No Dues" certification',
    'Conduct Exit interview',
    'Revoke access to databases',
    'Block work email ID',
    'Prepare formats',
    'Print formats',
    'Collate and print goals',
    'Prepare and email schedule',
    'Follow up on progress',
    'Collection of Appraisal forms',
    'Issue extension letter',
    'Background Check',
    'References Check',
    'Collect completed Appraisals',
    'Complete contract revisions',
    'Confirm End of Probation Date'
  );


/* check hrcase_civicrm_post for more info */
SELECT @caseCompId := id FROM `civicrm_component` where `name` like 'CiviCase';
SELECT @option_group_id_activity_type := max(id) from civicrm_option_group where name = 'activity_type';
SELECT @max_val := MAX(ROUND(op.value)) FROM civicrm_option_value op WHERE op.option_group_id  =
   @option_group_id_activity_type;

INSERT INTO
   `civicrm_option_value` (`option_group_id`, `label`,`name`, `value`,  `grouping`, `filter`, `is_default`,
   `weight`, `description`, `is_optgroup`, `is_reserved`, `is_active`, `component_id` )
VALUES
(@option_group_id_activity_type, 'Revoke access to databases','Revoke access to databases',
  (SELECT @max_val := @max_val+1), NULL, 0,  0, (SELECT @max_val := @max_val+1), '',  0, 0, 1, @caseCompId ),
(@option_group_id_activity_type, 'Block work email ID','Block work email ID',
  (SELECT @max_val := @max_val+1), NULL, 0,  0, (SELECT @max_val := @max_val+1), '',  0, 0, 1, @caseCompId );

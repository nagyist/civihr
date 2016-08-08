<?php

return array (
  array (
    'name' => 'Exiting',
    'entity' => 'CaseType',
    'params' =>
      array (
        'name' => 'Exiting',
        'title' => 'Exiting',
        'is_reserved' => 1,
        'is_active' => 1,
        'weight' => 6,
        'definition' =>
          [
            'activityTypes' => [
              ['name' => 'Open Case', 'max_instances' => 1],
              ['name' => 'Follow up'],
              ['name' => 'Change Case Type'],
              ['name' => 'Change Case Status'],
              ['name' => 'Change Case Start Date'],
              ['name' => 'Link Cases'],
              ['name' => 'Schedule Exit Interview'],
              ['name' => 'Get "No Dues" certification'],
              ['name' => 'Conduct Exit interview'],
              ['name' => 'Revoke access to databases'],
              ['name' => 'Block work email ID'],
              ['name' => 'Background Check'],
              ['name' => 'References Check']
            ],

            'activitySets' => [
              [
                'name'   => 'standard_timeline',
                'label' => 'Standard Timeline',
                'timeline' => 1,
                'activityTypes' => [
                  [
                    'name' => 'Schedule Exit Interview',
                    'reference_offset' => -10
                  ],
                  [
                    'name' => 'Get "No Dues" certification',
                    'reference_offset' => -7
                  ],
                  [
                    'name' => 'Conduct Exit interview',
                    'reference_offset' => -3
                  ],
                  [
                    'name' => 'Revoke access to databases',
                    'reference_offset' => 0
                  ],
                  [
                    'name' => 'Block work email ID',
                    'reference_offset' => 0
                  ]
                ]
              ]
            ],

            'caseRoles' => [
              ['name' => 'HR Manager', 'creator' => 1, 'manager' => 1]
            ]
          ]
      ),
  ),

  array (
    'name' => 'Joining',
    'entity' => 'CaseType',
    'params' =>
      array (
        'name' => 'Joining',
        'title' => 'Joining',
        'is_reserved' => 1,
        'is_active' => 1,
        'weight' => 7,
        'definition' =>
          [
            'activityTypes' => [
              ['name' => 'Open Case', 'max_instances' => 1],
              ['name' => 'Follow up'],
              ['name' => 'Change Case Type'],
              ['name' => 'Change Case Status'],
              ['name' => 'Change Case Start Date'],
              ['name' => 'Link Cases'],
              ['name' => 'Schedule joining date'],
              ['name' => 'Issue appointment letter'],
              ['name' => 'Fill Employee Details Form'],
              ['name' => 'Submission of ID/Residence proofs and photos'],
              ['name' => 'Program and work induction by program supervisor'],
              ['name' => 'Enter employee data in CiviHR'],
              ['name' => 'Group Orientation to organization, values, policies'],
              ['name' => 'Probation appraisal (start probation workflow)'],
              ['name' => 'Background Check'],
              ['name' => 'References Check'],
              ['name' => 'Confirm End of Probation Date'],
              ['name' => 'Start Probation workflow']
            ],

            'activitySets' => [
              [
                'name'   => 'standard_timeline',
                'label' => 'Standard Timeline',
                'timeline' => 1,
                'activityTypes' => [
                  [
                    'name' => 'Schedule joining date',
                    'reference_offset' => -10
                  ],
                  [
                    'name' => 'Issue appointment letter',
                    'reference_offset' => -10
                  ],
                  [
                    'name' => 'Fill Employee Details Form',
                    'reference_offset' => -10
                  ],
                  [
                    'name' => 'Submission of ID/Residence proofs and photos',
                    'reference_offset' => -10
                  ],
                  [
                    'name' => 'Enter employee data in CiviHR',
                    'reference_offset' => -7
                  ],
                  [
                    'name' => 'Program and work induction by program supervisor',
                    'reference_offset' => -10
                  ],
                  [
                    'name' => 'Group Orientation to organization, values, policies',
                    'reference_offset' => 7
                  ],
                  [
                    'name' => 'Confirm End of Probation Date',
                    'reference_offset' => 30
                  ],
                  [
                    'name' => 'Start Probation workflow',
                    'reference_offset' => 30
                  ]
                ]
              ]
            ],

            'caseRoles' => [
              ['name' => 'HR Manager', 'creator' => 1, 'manager' => 1],
              ['name' => 'Recruiting Manager', 'creator' => 1, 'manager' => 1]
            ]
          ]
      ),
  ),

  array (
    'name' => 'Probation',
    'entity' => 'CaseType',
    'params' =>
      array (
        'name' => 'Probation',
        'title' => 'Probation',
        'is_reserved' => 1,
        'is_active' => 1,
        'weight' => 8,
        'definition' =>
          [
            'activityTypes' => [
              ['name' => 'Open Case', 'max_instances' => 1],
              ['name' => 'Follow up'],
              ['name' => 'Change Case Type'],
              ['name' => 'Change Case Status'],
              ['name' => 'Change Case Start Date'],
              ['name' => 'Link Cases'],
              ['name' => 'Conduct appraisal'],
              ['name' => 'Collection of appraisal paperwork'],
              ['name' => 'Issue confirmation/warning letter'],
              ['name' => 'Background Check'],
              ['name' => 'References Check']
            ],

            'activitySets' => [
              [
                'name'   => 'standard_timeline',
                'label' => 'Standard Timeline',
                'timeline' => 1,
                'activityTypes' => [
                  [
                    'name' => 'Conduct appraisal',
                    'reference_offset' => -24
                  ],
                  [
                    'name' => 'Collection of appraisal paperwork',
                    'reference_offset' => -3
                  ],
                  [
                    'name' => 'Issue confirmation/warning letter',
                    'reference_offset' => 0
                  ]
                ]
              ]
            ],

            'caseRoles' => [
              ['name' => 'HR Manager', 'creator' => 1, 'manager' => 1],
              ['name' => 'Line Manager']
            ]
          ]
      ),
  ),

  array (
    'name' => 'Appraisal',
    'entity' => 'CaseType',
    'params' =>
      array (
        'name' => 'Appraisal',
        'title' => 'Appraisal',
        'is_reserved' => 1,
        'is_active' => 1,
        'weight' => 9,
        'definition' =>
          [
            'activityTypes' => [
              ['name' => 'Open Case', 'max_instances' => 1],
              ['name' => 'Follow up'],
              ['name' => 'Change Case Type'],
              ['name' => 'Change Case Status'],
              ['name' => 'Change Case Start Date'],
              ['name' => 'Link Cases'],
              ['name' => 'Prepare formats'],
              ['name' => 'Print formats'],
              ['name' => 'Collate and print goals'],
              ['name' => 'Prepare and email schedule'],
              ['name' => 'Follow up on progress'],
              ['name' => 'Collection of Appraisal forms'],
              ['name' => 'Issue extension letter'],
              ['name' => 'Background Check'],
              ['name' => 'References Check'],
              ['name' => 'Collect completed Appraisals'],
              ['name' => 'Complete contract revisions']
            ],

            'activitySets' => [
              [
                'name'   => 'standard_timeline',
                'label' => 'Standard Timeline',
                'timeline' => 1,
                'activityTypes' => [
                  [
                    'name' => 'Prepare and email schedule',
                    'reference_offset' => -60
                  ],
                  [
                    'name' => 'Follow up on progress',
                    'reference_offset' => -30
                  ],
                  [
                    'name' => 'Collect completed Appraisals',
                    'reference_offset' => -15
                  ],
                  [
                    'name' => 'Complete contract revisions',
                    'reference_offset' => 0
                  ]
                ]
              ]
            ],

            'caseRoles' => [
              ['name' => 'HR Manager', 'creator' => 1, 'manager' => 1]
            ]
          ]
      ),
  )
);

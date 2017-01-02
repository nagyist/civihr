define([
  'common/angular',
  'common/angularBootstrap',
  'common/text-angular',
  'common/models/option-group',
  'common/modules/dialog',
  'leave-absences/shared/ui-router',
  'leave-absences/my-leave/modules/config',
  'leave-absences/my-leave/components/my-leave',
  'leave-absences/my-leave/components/my-leave-calendar',
  'leave-absences/my-leave/components/my-leave-report',
  'leave-absences/shared/models/absence-period-model',
  'leave-absences/shared/models/absence-type-model',
  'leave-absences/shared/models/entitlement-model',
  'leave-absences/shared/models/leave-request-model',
  'leave-absences/shared/models/calendar-model',
  'leave-absences/shared/models/absence-period-model',
  'leave-absences/shared/models/absence-type-model',
], function (angular) {
  angular.module('my-leave', [
    'ngResource',
    'ngAnimate',
    'ui.router',
    'ui.bootstrap',
    'textAngular',
    'common.dialog',
    'common.models',
    'my-leave.config',
    'my-leave.components',
    'my-leave.directives',
    'leave-absences.models',
  ])
  .run(['$log', function ($log) {
    $log.debug('app.run');
  }]);

  return angular;
});

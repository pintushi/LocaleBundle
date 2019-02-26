define([
    'underscore',
    'oroui/js/mediator',
    'oroui/js/messenger',
    'orotranslation/js/translator',
    './mass-action'
], function(_, mediator, messenger, __, MassAction) {
    'use strict';

    var FrontendMassAction;

    /**
     * Frontend mass action class.
     *
     * @export  oro/datagrid/action/frontend-mass-action
     * @class   pintushi.grid.action.FrontendMassAction
     * @extends pintushi.grid.action.MassAction
     */
    FrontendMassAction = MassAction.extend({
        /**
         * @inheritDoc
         */
        constructor: function FrontendMassAction() {
            FrontendMassAction.__super__.constructor.apply(this, arguments);
        },

        /**
         * @inheritDoc
         */
        execute: function() {
            var selectionState = this.datagrid.getSelectionState();
            if (selectionState.selectedIds.length === 0 && selectionState.inset) {
                messenger.notificationFlashMessage('warning', __(this.messages.empty_selection));
            } else {
                mediator.trigger('datagrid:mass:frontend:execute:' + this.datagrid.name, this);
                this.$el.dropdown('toggle');
            }
        },

        /**
         * @inheritDoc
         */
        dispose: function() {
            if (this.disposed) {
                return;
            }

            mediator.off('datagrid:mass:frontend:execute:' + this.datagrid.name);

            MassAction.__super__.dispose.call(this);
        }
    });

    return FrontendMassAction;
});

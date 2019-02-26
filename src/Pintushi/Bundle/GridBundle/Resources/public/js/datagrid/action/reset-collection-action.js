define([
    './abstract-action'
], function(AbstractAction) {
    'use strict';

    var ResetCollectionAction;

    /**
     * Resets collection to initial state
     *
     * @export  oro/datagrid/action/reset-collection-action
     * @class   pintushi.grid.action.ResetCollectionAction
     * @extends pintushi.grid.action.AbstractAction
     */
    ResetCollectionAction = AbstractAction.extend({
        /** @property oro.PageableCollection */
        collection: undefined,

        /**
         * @inheritDoc
         */
        constructor: function ResetCollectionAction() {
            ResetCollectionAction.__super__.constructor.apply(this, arguments);
        },

        /**
         * Initialize action
         *
         * @param {Object} options
         * @param {oro.PageableCollection} options.collection Collection
         * @throws {TypeError} If collection is undefined
         */
        initialize: function(options) {
            var opts = options || {};

            if (!opts.datagrid) {
                throw new TypeError('"datagrid" is required');
            }
            this.collection = opts.datagrid.collection;

            ResetCollectionAction.__super__.initialize.apply(this, arguments);
        },

        /**
         * Execute reset collection
         */
        execute: function() {
            this.collection.updateState(this.collection.initialState);
            this.collection.fetch({reset: true});
        }
    });

    return ResetCollectionAction;
});

define([
    './abstract-action'
], function(AbstractAction) {
    'use strict';

    var RefreshCollectionAction;

    /**
     * Refreshes collection
     *
     * @export  oro/datagrid/action/refresh-collection-action
     * @class   pintushi.grid.action.RefreshCollectionAction
     * @extends pintushi.grid.action.AbstractAction
     */
    RefreshCollectionAction = AbstractAction.extend({
        /** @property oro.PageableCollection */
        collection: undefined,

        /**
         * @inheritDoc
         */
        constructor: function RefreshCollectionAction() {
            RefreshCollectionAction.__super__.constructor.apply(this, arguments);
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

            RefreshCollectionAction.__super__.initialize.apply(this, arguments);
        },

        /**
         * Execute refresh collection
         */
        execute: function() {
            this.datagrid.setAdditionalParameter('refresh', true);
            this.collection.fetch({reset: true});
            this.datagrid.removeAdditionalParameter('refresh');
        }
    });

    return RefreshCollectionAction;
});

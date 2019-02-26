define([
    'oro/datagrid/cell/html-cell',
    'orodatagrid/js/datagrid/formatter/phone-formatter'
], function(HtmlCell, PhoneFormatter) {
    'use strict';

    var PhoneCell;

    /**
     * Phone cell
     *
     * @export  oro/datagrid/cell/phone-cell
     * @class   pintushi.grid.cell.PhoneCell
     * @extends pintushi.grid.cell.HtmlCell
     */
    PhoneCell = HtmlCell.extend({
        /** @property */
        className: 'phone-cell',

        /** @property */
        events: {
            'click a': 'stopPropagation'
        },

        /**
         @property {(Backgrid.PhoneFormatter|Object|string)}
         */
        formatter: new PhoneFormatter(),

        /**
         * @inheritDoc
         */
        constructor: function PhoneCell() {
            PhoneCell.__super__.constructor.apply(this, arguments);
        },

        /**
         * If don't stop propagation click will select row
         */
        stopPropagation: function(e) {
            e.stopPropagation();
        }
    });

    return PhoneCell;
});

define([
    './string-cell'
], function(StringCell) {
    'use strict';

    var TimeCell;

    /**
     * Time column cell
     *
     * @export  oro/datagrid/cell/time-cell
     * @class   pintushi.grid.cell.TimeCell
     * @extends pintushi.grid.cell.StringCell
     */
    TimeCell = StringCell.extend({
        type: 'time',

        className: 'time-cell',

        /**
         * @inheritDoc
         */
        constructor: function TimeCell() {
            TimeCell.__super__.constructor.apply(this, arguments);
        }
    });

    return TimeCell;
});

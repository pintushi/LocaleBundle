define([
    './string-cell',
    'backgrid'
], function(StringCell, Backgrid) {
    'use strict';

    var HtmlCell;

    /**
     * Html column cell. Added missing behaviour.
     *
     * @export  oro/datagrid/cell/html-cell
     * @class   pintushi.grid.cell.HtmlCell
     * @extends pintushi.grid.cell.StringCell
     */
    HtmlCell = StringCell.extend({
        /**
         * use a default implementation to do not affect html content
         * @property {(Backgrid.CellFormatter)}
         */
        formatter: new Backgrid.CellFormatter(),

        /**
         * @inheritDoc
         */
        constructor: function HtmlCell() {
            HtmlCell.__super__.constructor.apply(this, arguments);
        },

        /**
         * Render a text string in a table cell. The text is converted from the
         * model's raw value for this cell's column.
         */
        render: function() {
            var value = this.model.get(this.column.get('name'));
            var formattedValue = this.formatter.fromRaw(value);
            this.$el.html(formattedValue);
            return this;
        }
    });

    return HtmlCell;
});

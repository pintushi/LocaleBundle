define([
    'backgrid',
    'underscore',
    'orotranslation/js/translator'
], function(Backgrid, _, __) {
    'use strict';

    var MultiSelectCell;

    /**
     * Cell able to display multiselect values.
     *
     * Requires income data format:
     * ```javascript
     * var cellValue = [<id-1>, <id-2>, ...];
     * ```
     *
     * Also please prepare and pass choices through cell configuration
     *
     * @export  oro/datagrid/cell/multi-select-cell
     * @class   pintushi.grid.cell.MultiSelectCell
     * @extends pintushi.grid.cell.StringCell
     */
    MultiSelectCell = Backgrid.StringCell.extend({
        /**
         * @property {string}
         */
        type: 'multiselect',

        /**
         * @property {string}
         */
        className: 'multi-select-cell',

        /**
         * @property {string}
         */
        ERROR_HTML: '<span style="color:red">' + __('Unexpected format') + '</span>',

        /**
         * @inheritDoc
         */
        constructor: function MultiSelectCell() {
            MultiSelectCell.__super__.constructor.apply(this, arguments);
        },

        /**
         * @inheritDoc
         */
        render: function() {
            var value = this.model.get(this.column.get('name'));
            var choices = this.choices;

            if (_.isString(value)) {
                try {
                    value = JSON.parse(value);
                } catch (e) {
                    this.$el.html(this.ERROR_HTML);
                    return this;
                }
            }

            if (value === null || value === void 0) {
                // assume empty
                value = [];
            }

            var html;
            try {
                html = value.length > 0 ? (
                    '<span class="multiselect-value-wrapper"><span class="value-item">' +
                    value
                        .map(function(item) {
                            return _.findKey(choices, function(value) {
                                return value === item;
                            });
                        })
                        .filter(function(item) {
                            return item;
                        })
                        .join('</span><span class="value-item">') +
                    '</span></span>'
                ) : '';
            } catch (e) {
                html = this.ERROR_HTML;
            }

            this.$el.html(html);

            return this;
        }
    });

    return MultiSelectCell;
});

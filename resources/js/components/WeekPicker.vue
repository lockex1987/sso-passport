<template>
    <div class="week-picker input-group w-auto cursor-pointer rounded">
        <div class="input-group-prepend">
            <span class="input-group-text bg-transparent border-right-0 pr-0">
                <i class="la la-lg la-calendar"></i>
            </span>
        </div>

        <input type="text"
               class="form-control bg-transparent border-left-0"
               :class="[isRequired ? '' : 'border-right-0']"
               readonly
               :placeholder="placeholder">

        <div class="input-group-append" v-if="!isRequired">
            <span class="input-group-text bg-transparent border-left-0 pl-0">
                <i class="la la-lg la-times text-danger"
                        @click.stop="clearDateFilter(true)"></i>
            </span>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        config: {
            type: Object,
            default: null
        },

        placeholder: {
            type: String,
            default: 'Chọn tuần'
        },

        isRequired: {
            type: Boolean,
            default: true
        },

        minDate: {
            type: Object,
            default: null
        },

        maxDate: {
            type: Object,
            default: null
        }
    },

    data() {
        return {
            options: {
                format: 'DD/MM/YYYY',
                // Chỉ chọn 1 ngày
                singleDatePicker: true,
                showDropdowns: true,
                locale: {
                    separator: ' - ',
                    applyLabel: 'Áp dụng',
                    cancelLabel: 'Hủy',
                    customRangeLabel: 'Tùy chỉnh',
                    fromLabel: 'Từ',
                    toLabel: 'Đến',
                    daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7'],
                    monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                    firstDay: 1
                }
            }
        };
    },

    mounted() {
        this.initDatePicker();
    },

    methods: {
        /**
             * Khởi tạo.
             */
        initDatePicker() {
            // cài đặt mindate và maxdate
            if (this.maxDate) {
                this.options.maxDate = this.maxDate;
            }
            if (this.minDate) {
                this.options.minDate = this.minDate;
            }

            // Khởi tạo tùy chọn
            // const options = Object.assign(this.options, this.config);

            $(this.$el)
                .daterangepicker(this.options)
                .on('apply.daterangepicker', this.applyDateFilter);

            // Thêm CSS cho popup
            const picker = $(this.$el).data('daterangepicker');
            picker.container.addClass('week-picker');
        },

        /**
             * Xử lý khi chọn thời gian.
             */
        applyDateFilter(evt, picker) {
            const date = picker.startDate;

            // Chỉnh lại ngày bắt đầu, ngày kết thúc là đầu tuần và cuối tuần
            const start = date.clone().startOf('isoWeek');
            const end = date.clone().endOf('isoWeek');

            this.setDate(start, end);
            this.$emit('change', {
                startDate: start.format('YYYY/MM/DD'),
                endDate: end.format('YYYY/MM/DD')
            });
        },

        /**
             * Xóa thời gian.
             * @params {Boolean} showEmitChange Có khi chúng ta cần clear một cách lặng lẽ
             */
        clearDateFilter(showEmitChange = false) {
            this.bindDateToInput('', '');
            if (showEmitChange) {
                this.$emit('change', {
                    startDate: null,
                    endDate: null
                });
            }
        },

        /**
             * Thiết lập ngày.
             */
        setDate(start, end) {
            $(this.$el).data('daterangepicker').setStartDate(start);
            $(this.$el).data('daterangepicker').setEndDate(end);
            this.bindDateToInput(start, end);
        },

        /**
             * Hiển thị date.
             */
        bindDateToInput(start, end) {
            if (start) {
                const s = start.format(this.options.format) + this.options.locale.separator + end.format(this.options.format);
                $(this.$el).find('input').val(s);
            } else {
                $(this.$el).find('input').val('');
            }
        }
    }
};
</script>


<style lang="scss">
    .week-picker {
        .form-control {
            width: 205px;
        }
    }

    .week-picker {
        tbody {
            tr {
                &:hover {
                    background-color: #EEE;
                }
            }
        }
    }
</style>

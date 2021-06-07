<template>
    <div class="date-range-picker input-group w-auto cursor-pointer rounded">
        <div class="input-group-prepend">
            <span class="input-group-text bg-transparent border-right-0 pr-0">
                <i class="la la-lg la-calendar"
                        :class="[iconClass || '']"></i>
            </span>
        </div>

        <input type="text"
                class="form-control bg-transparent border-left-0"
                :class="[isRequired ? '' : 'border-right-0', inputClass || '']"
                readonly
                :placeholder="placeholder">

        <div class="input-group-append" v-if="!isRequired">
            <span class="input-group-text bg-transparent border-left-0 pl-0">
                <i class="la la-lg la-times text-danger"
                        :class="[clearClass || '']"
                        @click.stop="clearDateRangeFilter(true)"></i>
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
            default: 'Tất cả thời gian'
        },

        isRequired: {
            type: Boolean,
            default: false
        },

        inputClass: {
            type: String,
            default: ''
        },

        iconClass: {
            type: String,
            default: ''
        },

        clearClass: {
            type: String,
            default: ''
        }
    },

    data() {
        const currentDate = moment();
        // const endOfWeek = moment().endOf('isoWeek');
        // const endOfMonth = moment().endOf('month');
        return {
            options: {
                format: 'DD/MM/YYYY',
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
                },
                ranges: {
                    'Hôm nay': [moment(), moment()],
                    'Hôm qua': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                    '7 ngày qua': [moment().subtract(6, 'days'), moment()],
                    '30 ngày qua': [moment().subtract(29, 'days'), moment()],
                    'Tuần này': [moment().startOf('isoWeek'), currentDate], // endOfWeek
                    'Tuần trước': [moment().subtract(1, 'week').startOf('isoWeek'), moment().subtract(1, 'week').endOf('isoWeek')],
                    'Tháng này': [moment().startOf('month'), currentDate], // endOfMonth
                    'Tháng trước': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
                },
                maxDate: currentDate
            }
        };
    },

    mounted() {
        this.initDateRangePicker();
    },

    methods: {
        /**
             * Khởi tạo.
             */
        initDateRangePicker() {
            Object.assign(this.options, this.config);

            $(this.$el)
                .daterangepicker(this.options)
                .on('apply.daterangepicker', this.applyDateRangeFilter);

            if (this.options.startDate && this.options.endDate) {
                const start = this.options.startDate;
                const end = this.options.endDate;

                const s = start.format(this.options.format) + this.options.locale.separator + end.format(this.options.format);
                $(this.$el).find('input').val(s);
            }
        },

        /**
             * Xử lý khi chọn khoảng thời gian.
             */
        applyDateRangeFilter(evt, picker) {
            const start = picker.startDate;
            const end = picker.endDate;

            const s = start.format(this.options.format) + this.options.locale.separator + end.format(this.options.format);
            $(this.$el).find('input').val(s);

            this.$emit('change', {
                startDate: start.format('YYYY/MM/DD'),
                endDate: end.format('YYYY/MM/DD')
            });
        },

        /**
             * Xóa chọn khoảng thời gian.
             * @params {Boolean} showEmitChange Có khi chúng ta cần clear một cách lặng lẽ
             */
        clearDateRangeFilter(showEmitChange = false) {
            $(this.$el).find('input').val('');

            if (showEmitChange) {
                this.$emit('change', {
                    startDate: null,
                    endDate: null
                });
            }
        },

        /**
             * Thiết lập ngày bắt đầu, ngày kết thúc.
             * @param start Ngày bắt đầu, là đối tượng moment
             * @param end Ngày kết thúc, là đối tượng moment
             */
        setStartAndEndDate(start, end) {
            $(this.$el).data('daterangepicker').setStartDate(start);
            $(this.$el).data('daterangepicker').setEndDate(end);

            const s = start.format(this.options.format) + this.options.locale.separator + end.format(this.options.format);
            $(this.$el).find('input').val(s);
        }
    }
};
</script>


<style lang="scss">
    .date-range-picker {
        .form-control {
            width: 205px;
        }
    }
</style>

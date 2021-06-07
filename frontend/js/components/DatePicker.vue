<template>
    <div class="date-picker input-group w-auto cursor-pointer rounded">
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
                   @click.stop="clearDateFilter(true)"></i>
            </span>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        placeholder: {
            type: String,
            default: 'Chọn ngày'
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
        },

        defaultDate: {
            type: Object,
            default: null
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
                showDropdowns: false,
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
            if (this.maxDate) {
                this.options.maxDate = this.maxDate;
            }
            if (this.minDate) {
                this.options.minDate = this.minDate;
            }

            $(this.$el)
                .daterangepicker(this.options)
                .on('apply.daterangepicker', this.applyDateFilter);

            if (this.defaultDate) {
                this.setDate(this.defaultDate);
            }
        },

        /**
             * Xử lý khi chọn thời gian.
             */
        applyDateFilter(evt, picker) {
            const date = picker.startDate;
            this.bindDateToInput(date);
            this.$emit('change', {
                date: date.format('YYYY/MM/DD')
            });
        },

        /**
             * Xóa thời gian.
             * @params {Boolean} showEmitChange Có khi chúng ta cần clear một cách lặng lẽ
             */
        clearDateFilter(showEmitChange = false) {
            this.bindDateToInput('');
            if (showEmitChange) {
                this.$emit('change', {
                    date: null
                });
            }
        },

        /**
             * Thiết lập ngày.
             * @param date
             */
        setDate(date) {
            $(this.$el).data('daterangepicker').setStartDate(date);
            $(this.$el).data('daterangepicker').setEndDate(date);
            this.bindDateToInput(date);
        },

        /**
             * Hiển thị date.
             */
        bindDateToInput(date) {
            if (date) {
                const s = date.format(this.options.format);
                $(this.$el).find('input').val(s);
            } else {
                $(this.$el).find('input').val('');
            }
        }
    }
};
</script>


<style lang="scss">
    .date-picker {
        .form-control {
            width: 110px;
        }
    }
</style>

<template>
    <div class="d-flex align-items-start date-range-picker-separate">
        <div class="validate-container d-inline-block field-width">
            <input type="text"
                    class="form-control"
                    :data-validation="(isStartRequired ? 'required|' : '') + 'date'"
                    ref="startDate"
                    :placeholder="'Ngày bắt đầu' + (isStartRequired ? ' (*)' : '')"/>
        </div>

        <span class="la la-arrow-right mx-3 mt-3"></span>

        <div class="validate-container d-inline-block field-width">
            <input type="text"
                    class="form-control"
                    :data-validation="(isEndRequired ? 'required|' : '') + 'date'"
                    ref="endDate"
                    :placeholder="'Ngày kết thúc' + (isEndRequired ? ' (*)' : '')"/>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        isStartRequired: {
            type: Boolean,
            default: true
        },

        isEndRequired: {
            type: Boolean,
            default: true
        },

        defaultStartDate: {
            type: Object,
            default: null
        },

        defaultEndDate: {
            type: Object,
            default: null
        },

        maxDaysSpan: {
            type: Number,
            default: 365 * 100
        },

        minStartDate: {
            type: Object,
            default: null
        },

        maxStartDate: {
            type: Object,
            default: null
        }
    },

    data() {
        const frontendDateFormat = 'DD/MM/YYYY';

        return {
            // Ngày bắt đầu (String)
            startDate: null,
            // Ngày kết thúc (String)
            endDate: null,
            // Định dạng ngày tháng hiển thị
            frontendDateFormat: frontendDateFormat,
            // Định dạng ngày tháng khi lưu, chuẩn ISO
            backendDateFormat: 'YYYY-MM-DD',
            // Cấu hình mặc định
            defaultDatepickerOptions: {
                singleDatePicker: true,
                autoUpdateInput: false,
                locale: {
                    format: frontendDateFormat,
                    separator: ' - ',
                    applyLabel: 'Áp dụng',
                    cancelLabel: 'Hủy',
                    customRangeLabel: 'Tùy chỉnh',
                    fromLabel: 'Từ',
                    toLabel: 'Đến',
                    daysOfWeek: ['CN', 'T2', 'T3', 'T4', 'T5', 'T6', '7'],
                    monthNames: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
                    firstDay: 1
                }
            }
        };
    },

    mounted() {
        this.init();
    },

    methods: {
        /**
             * Khởi tạo.
             */
        init() {
            this.initStartDate(this.defaultEndDate);
            this.initEndDate(this.defaultStartDate);
        },

        /**
             * Chọn ngày bắt đầu.
             */
        initStartDate(maxDate) {
            // Xóa instance cũ
            if ($(this.$refs.startDate).data('daterangepicker') != null) {
                $(this.$refs.startDate).data('daterangepicker').remove();
            }

            // Khởi tạo tùy chọn
            const options = Object.assign({}, this.defaultDatepickerOptions);

            // Không thể để minDate, maxDate là các giá trị null, nếu không chỉ thiết lập được tháng hiện tại
            if (maxDate) {
                options.minDate = moment(maxDate, this.frontendDateFormat).subtract(this.maxDaysSpan, 'days');
                options.maxDate = moment(maxDate, this.frontendDateFormat);
            } else {
                options.minDate = moment().subtract(this.maxDaysSpan, 'days');
                options.maxDate = moment().add(this.maxDaysSpan, 'days');
            }

            // Valiate khoảng ngày
            if (this.minStartDate) {
                if (this.minStartDate.isAfter(options.minDate)) {
                    options.minDate = this.minStartDate;
                }
            }

            if (this.maxStartDate) {
                if (this.maxStartDate.isBefore(options.maxDate)) {
                    options.maxDate = this.maxStartDate;
                }
            }

            // Hàm thực hiện khi người dùng chọn ngày
            const callback = (evt, picker) => {
                const chosenDate = picker.startDate;
                let value;

                if (chosenDate.isValid()) {
                    this.startDate = chosenDate.format(this.backendDateFormat);
                    value = chosenDate.format(this.frontendDateFormat);
                    $(this.$refs.startDate).val(value);
                } else {
                    this.startDate = null;
                    value = null;
                    $(this.$refs.startDate).val('');
                }

                const el = this.$refs.startDate;
                if (el.matches('.has-error')) {
                    CV.clearSingleErrorMessage(el);
                }
                const errorMessage = CV.getValidateError(el);
                if (errorMessage) {
                    CV.showError(el, errorMessage);
                }

                this.initEndDate(value);
            };

            // Khởi tạo instance
            $(this.$refs.startDate).daterangepicker(options)
                .on('apply.daterangepicker', callback);
        },

        /**
             * Chọn ngày kết thúc.
             */
        initEndDate(minDate) {
            const options = Object.assign({}, this.defaultDatepickerOptions);

            if ($(this.$refs.endDate).data('daterangepicker') != null) {
                $(this.$refs.endDate).data('daterangepicker').remove();
            }

            // Không thể để minDate, maxDate là các giá trị null, nếu không chỉ thiết lập được tháng hiện tại
            if (minDate) {
                options.minDate = moment(minDate, this.frontendDateFormat);
                options.maxDate = moment(minDate, this.frontendDateFormat).add(this.maxDaysSpan, 'days');
            } else {
                options.minDate = moment().subtract(this.maxDaysSpan, 'days');
                options.maxDate = moment().add(this.maxDaysSpan, 'days');
            }

            const callback = (evt, picker) => {
                const chosenDate = picker.startDate;
                let value;

                if (chosenDate.isValid()) {
                    this.endDate = chosenDate.format(this.backendDateFormat);
                    value = chosenDate.format(this.frontendDateFormat);
                    $(this.$refs.endDate).val(value);
                } else {
                    this.endDate = null;
                    value = null;
                    $(this.$refs.endDate).val('');
                }

                const el = this.$refs.endDate;
                if (el.matches('.has-error')) {
                    CV.clearSingleErrorMessage(el);
                }
                const errorMessage = CV.getValidateError(el);
                if (errorMessage) {
                    CV.showError(el, errorMessage);
                }

                this.initStartDate(value);
            };

            $(this.$refs.endDate).daterangepicker(options)
                .on('apply.daterangepicker', callback);
        },

        /**
             * Lấy giá trị ngày bắt đầu.
             */
        getStartDate() {
            return this.startDate;
        },

        /**
             * Lấy giá trị ngày kết thúc.
             */
        getEndDate() {
            return this.endDate;
        },

        /**
             * Thiết lập ngày bắt đầu.
             * @params strValue Xâu ngày tháng với định dạng YYYY-MM-DD HH:mm:ss
             */
        setStartDate(strValue) {
            let formatedValue;
            if (strValue) {
                const temp = moment(strValue);
                this.startDate = temp.format(this.backendDateFormat);
                formatedValue = temp.format(this.frontendDateFormat);
                $(this.$refs.startDate).val(formatedValue);
            } else {
                this.startDate = null;
                formatedValue = null;
                $(this.$refs.startDate).val('');
            }

            this.initEndDate(formatedValue);
        },

        /**
             * Thiết lập ngày kết thúc.
             * @params strValue Xâu ngày tháng với định dạng YYYY-MM-DD HH:mm:ss
             */
        setEndDate(strValue) {
            let formatedValue;
            if (strValue) {
                const temp = moment(strValue);
                this.endDate = temp.format(this.backendDateFormat);
                formatedValue = temp.format(this.frontendDateFormat);
                $(this.$refs.endDate).val(formatedValue);
            } else {
                this.endDate = null;
                formatedValue = null;
                $(this.$refs.endDate).val('');
            }

            this.initStartDate(formatedValue);
        }
    }
};
</script>


<style scoped lang="scss">
    .date-range-picker-separate {
        .field-width {
            max-width: 150px;
        }
    }
</style>

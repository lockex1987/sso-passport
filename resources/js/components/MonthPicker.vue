<template>
    <div class="month-picker dropdown">
        <div class="input-group w-auto cursor-pointer rounded dropdown-toggle no-caret-right-down" data-toggle="dropdown">
            <div class="input-group-prepend">
                <span class="input-group-text bg-transparent border-right-0 pr-0">
                    <i class="la la-lg la-calendar"></i>
                </span>
            </div>

            <input type="text"
                    class="form-control bg-transparent border-left-0"
                    readonly
                    :placeholder="placeholder">
        </div>

        <div class="dropdown-menu">
            <div class="screen p-2" @click.stop="">
                <div class="d-flex justify-content-between align-items-center">
                    <i class="la la-angle-left cursor-pointer hover:text-info"
                            @click="tempYear--"
                            :class="[tempYear <= minYear ? 'invisible' : '']"></i>

                    <span class="font-weight-500">
                        {{tempYear}}
                    </span>

                    <i class="la la-angle-right cursor-pointer hover:text-info"
                            @click="tempYear++"
                            :class="[tempYear >= maxYear ? 'invisible' : '']"></i>
                </div>

                <div class="d-flex flex-wrap mt-4">
                    <div v-for="m in monthList"
                            :key="m"
                            class="month-item text-center p-1"
                            :class="[tempYear == maxYear && m > maxMonth ? 'invisible' : '']">
                        <div class="hover:bg-light hover:text-secondary rounded py-3 cursor-pointer"
                                :class="[tempYear == chosenYear && m == chosenMonth ? 'bg-primary text-white' : '']"
                                @click="chooseMonth(m)">
                            {{m}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        placeholder: {
            type: String,
            default: 'Chọn tháng'
        },

        minYear: {
            type: Number,
            default: 2000
        },

        maxYear: {
            type: Number,
            default: null
        },

        maxYearCurrent: {
            type: Boolean,
            default: true
        }
    },

    data() {
        // 12 tháng
        const list = [];
        for (let i = 1; i <= 12; i++) {
            list.push(i);
        }

        return {
            // Năm được chọn
            chosenYear: null,
            // Tháng được chọn
            chosenMonth: null,
            // Danh sách tháng
            monthList: list,
            // Năm khi chọn
            tempYear: null,
            // Tháng lớn nhất
            maxMonth: null
        };
    },

    mounted() {
        // Hiện tại
        const current = new Date();
        const currentYear = current.getFullYear();

        // Điều chỉnh lại maxYear
        if (!this.maxYear) {
            if (this.maxYearCurrent) {
                this.maxYear = currentYear;
            }
        }

        this.maxMonth = current.getMonth() + 1;

        // Năm khi chọn
        this.tempYear = currentYear;
    },

    methods: {
        /**
             * Chọn tháng.
             */
        chooseMonth(m) {
            this.chosenMonth = m;
            this.chosenYear = this.tempYear;

            // Ẩn dropdown
            $(this.$el.querySelector('.dropdown-toggle')).dropdown('toggle');

            this.$el.querySelector('input').value = (this.chosenMonth < 10 ? '0' : '') + this.chosenMonth + '/' + this.chosenYear;
            this.$emit('change', {
                month: this.chosenMonth,
                year: this.chosenYear
            });
        }
    }
};
</script>


<style lang="scss" scoped>
    .month-picker {
        .form-control {
            width: 110px;
        }

        .screen {
            width: 330px;
        }

        .month-item {
            width: 25%;
        }
    }
</style>

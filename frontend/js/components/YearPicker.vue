<template>
    <div class="year-picker dropdown">
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
                            @click="gotoPreviousScreen()"
                            :class="[startYear <= minYear ? 'invisible' : '']"></i>

                    <span class="font-weight-500">
                        {{yearRangeLabel}}
                    </span>

                    <i class="la la-angle-right cursor-pointer hover:text-info"
                            @click="gotoNextScreen()"
                            :class="[endYear >= maxYear ? 'invisible' : '']"></i>
                </div>

                <div class="d-flex flex-wrap mt-4">
                    <div v-for="y in shownYearList"
                            :key="y"
                            class="year-item text-center py-3 cursor-pointer hover:bg-light hover:text-secondary rounded"
                            :class="[y < minYear || y > maxYear ? 'invisible' : '', y == chosenYear ? 'bg-primary text-white' : '']"
                            @click="chooseYear(y)">
                        {{y}}
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
            default: 'Chọn năm'
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
        return {
            // Năm được chọn
            chosenYear: null,
            // Số năm hiển thị mỗi lần
            itemPerPage: 10,
            // Năm đầu tiên, năm cuối cùng hiển thị
            startYear: null,
            endYear: null
        };
    },

    computed: {
        /**
             * Những năm hiển thị.
             */
        shownYearList() {
            const list = [];
            for (let i = this.startYear; i <= this.endYear; i++) {
                list.push(i);
            }
            return list;
        },

        /**
             * Nhãn ở header.
             */
        yearRangeLabel() {
            const sy = Math.max(this.startYear, this.minYear);
            const ey = Math.min(this.endYear, this.maxYear);
            return sy + ' - ' + ey;
        }
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

        // Năm bắt đầu, năm kết thúc
        this.startYear = currentYear - (currentYear % this.itemPerPage);
        this.endYear = this.startYear + this.itemPerPage - 1;

        // Năm được chọn
        // this.chosenYear = currentYear;
    },

    methods: {
        /**
             * Chuyển đến màn hình trước đó.
             */
        gotoPreviousScreen() {
            this.startYear -= this.itemPerPage;
            this.endYear -= this.itemPerPage;
        },

        /**
             * Chuyển đến màn hình tiếp theo.
             */
        gotoNextScreen() {
            this.startYear += this.itemPerPage;
            this.endYear += this.itemPerPage;
        },

        /**
             * Chọn năm.
             */
        chooseYear(y) {
            this.chosenYear = y;

            // Ẩn dropdown
            $(this.$el.querySelector('.dropdown-toggle')).dropdown('toggle');
            this.$el.querySelector('input').value = this.chosenYear;
            this.$emit('change', this.chosenYear);
        }
    }
};
</script>


<style lang="scss" scoped>
    .year-picker {
        .form-control {
            width: 110px;
        }

        .screen {
            width: 300px;
        }

        .year-item {
            width: 25%;
        }
    }
</style>

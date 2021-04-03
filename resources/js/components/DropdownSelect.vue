<template>
    <div class="dropdown-select dropdown"
            :class="[wrapperClass || '']">
        <div type="text"
                class="custom-select dropdown-toggle no-caret-right-down text-truncate"
                :class="[inputClass || '']"
                data-toggle="dropdown">
            <span v-if="!selectedValues" class="font-weight-500">
                {{placeholder}}
            </span>
            <span>
                {{selectedValues}}
            </span>
        </div>

        <!-- Nút xóa -->
        <span class="la la-times text-danger d-none"></span>

        <!-- Dropdown -->
        <div class="dropdown-menu shadow-sm">
            <!-- Tìm kiếm -->
            <div class="p-2" v-if="hasSearch && options.length > 5">
                <input type="text" class="form-control" v-model.trim="searchText"/>
            </div>

            <!-- Thêm tùy chọn -->
            <div class="p-2" v-if="dynamicOptions">
                <div class="input-group">
                    <div class="input-group-prepend">
                        <span class="input-group-text bg-transparent border-right-0">
                            <i class="la la-plus"></i>
                        </span>
                    </div>
                    <input type="text"
                            class="form-control border-left-0 pl-0"
                            v-model.trim="newOption"
                            :placeholder="addPlaceholder"
                            @keydown.enter.prevent="addNewOption($event)"/>
                </div>

                <div class="text-danger font-size-0.875" v-if="duplicatedOption">
                    Tùy chọn đã tồn tại
                </div>
            </div>

            <!-- Thông báo -->
            <div v-show="!dynamicOptions && filterOptions.length == 0" class="text-danger p-2">
                Không tìm thấy bản ghi
            </div>

            <div class="item-list overflow-auto custom-scrollbar">
                <!-- Checkbox chọn tất cả -->
                <div class="dropdown-item p-0" v-if="hasSelectAll">
                    <label class="d-block custom-control custom-control-lg custom-control-animated custom-control-highlighted custom-control-outlined text-truncate py-1 item mb-0 custom-checkbox"
                            :class="[dynamicOptions ? 'pr-4' : 'pr-2']"
                            @click="stopClosingDropdown($event)"
                            v>
                        <input type="checkbox"
                                class="custom-control-input"
                                v-model='checkedAll'>

                        <span class="item-label custom-control-label font-weight-500">Chọn tất cả</span>
                    </label>
                </div>

                <!-- Danh sách dữ liệu -->
                <div class="dropdown-item p-0"
                        v-for="(opt, idx) in filterOptions"
                        :key="opt.id">
                    <label class="d-block custom-control custom-control-lg custom-control-animated custom-control-highlighted custom-control-outlined text-truncate py-1 item mb-0"
                            :class="[multiple ? 'custom-checkbox' : 'custom-radio', dynamicOptions ? 'pr-4' : 'pr-2']"
                            :title="opt.name"
                            @click="stopClosingDropdown($event)">
                        <input type="checkbox"
                                class="custom-control-input"
                                v-model='opt.checked'
                                v-if="multiple"
                                @change="notifyChange()">
                        <input type="radio"
                                class="custom-control-input"
                                :value="opt.id"
                                v-model='radioObj.id'
                                @change="notifyChange()"
                                v-else>

                        <!-- Icon đánh dấu phần tử được chọn trong trường hợp chọn 1 -->
                        <i class="la la-check text-success position-absolute check-icon" v-if="!multiple && radioObj.id == opt.id"></i>

                        <span class="item-label" :class="[multiple ? 'custom-control-label' : '']">{{opt.name}}</span>

                        <i class="la la-times text-danger cursor-pointer position-absolute remove-icon"
                                v-if="dynamicOptions"
                                @click.stop="removeOption(idx, $event)"></i>
                    </label>
                </div>
            </div>
        </div>
    </div>
</template>


<script>
export default {
    props: {
        // Chọn nhiều
        multiple: {
            type: Boolean,
            default: true
        },
        // Danh sách tùy chọn
        options: {
            type: Array,
            default: []
        },
        // Placeholder khi chưa có giá trị nào
        placeholder: {
            type: String,
            default: ''
        },
        // Phải là kiểu object hoặc array thì khi thay đổi ở component thì component cha mới nhận được giá trị
        radioObj: {
            type: Object,
            default: {}
        },
        // Có ô tìm kiếm hay không
        hasSearch: {
            type: Boolean,
            default: false
        },
        // Có cho phép thêm, xóa tùy chọn hay không
        dynamicOptions: {
            type: Boolean,
            default: false
        },
        // Placeholder của thêm tùy chọn
        addPlaceholder: {
            type: String,
            default: ''
        },
        // CSS class để chỉnh giao diện
        inputClass: {
            type: String,
            default: ''
        },
        // CSS class để chỉnh giao diện
        wrapperClass: {
            type: String,
            default: ''
        },
        // Có checkbox chọn tất cả hay không
        hasSelectAll: {
            type: Boolean,
            default: false
        }
    },

    data() {
        return {
            // Từ khóa tìm kiếm
            searchText: '',
            // Nhập tùy chọn mới
            newOption: ''
        };
    },

    computed: {
        /**
             * Giá trị (có thể nhiều) được chọn.
             */
        selectedValues() {
            if (this.multiple) {
                return this.options.filter(opt => opt.checked).map(opt => opt.name).join(', ');
            }

            const obj = this.options.find(opt => opt.id == this.radioObj.id);
            if (obj) {
                return obj.name;
            }

            return '';
        },

        /**
             * Danh sách dữ liệu mà đã lọc.
             */
        filterOptions() {
            if (!this.searchText) {
                return this.options;
            }

            const text = this.searchText.toLowerCase();
            return this.options.filter(opt => opt.name.toLowerCase().includes(text));
        },

        /**
             * Kiểm tra trùng khi thêm mới tùy chọn.
             */
        duplicatedOption() {
            const id = this.newOption.toLowerCase();
            const obj = this.options.find(opt => opt.id.toLowerCase() == id);
            if (obj) {
                return true;
            }
            return false;
        },

        /**
             * Kiểm tra khác empty khi thêm mới tùy chọn.
             */
        emptyOption() {
            const id = this.newOption.trim();
            if (!id.length) {
                return true;
            }
            return false;
        },

        /**
             * Checkbox chọn tất cả.
             */
        checkedAll: {
            get: function () {
                return this.options.every(e => e.checked);
            },

            set: function (value) {
                this.options.forEach(e => {
                    e.checked = value;
                });

                this.notifyChange();
            }
        }
    },

    methods: {
        /**
             * Không đóng dropdown khi click.
             */
        stopClosingDropdown(evt) {
            if (this.multiple) {
                evt.stopPropagation();
            }
        },

        /**
             * Thông báo thay đổi.
             */
        notifyChange() {
            this.$emit('change');
        },

        /**
             * Thêm tùy chọn mới.
             */
        addNewOption() {
            // Nếu trùng thì dừng lại
            if (this.duplicatedOption) {
                return;
            }

            // Nếu empty option thi dừng lại
            if (this.emptyOption) {
                return;
            }

            // Thêm mới tùy chọn, mặc định là chọn luôn
            this.options.push({
                id: this.newOption, // .toLowerCase()
                name: this.newOption,
                checked: true
            });

            // Reset để thêm tiếp
            this.newOption = '';

            // Thông báo thay đổi
            this.notifyChange();
        },

        /**
             * Xóa tùy chọn.
             */
        removeOption(idx) {
            // const id = this.filterOptions[idx].id;
            // const originalIndex = this.options.findIndex(opt => opt.id == id);
            this.options.splice(idx, 1);

            // Thông báo thay đổi
            this.notifyChange();
        },

        /**
             * Trả về ID giá trị (có thể nhiều) được chọn.
             */
        getSelected() {
            if (this.multiple) {
                return this.options.filter(opt => opt.checked).map(opt => opt.id);
            }
            return this.radioObj.id;
        }
    }
};
</script>


<style scoped lang="scss">
    .dropdown-select {
        .item-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .dropdown-toggle {
            width: 180px;
        }

        .dropdown-item {
            overflow-y: hidden;
            max-width: 400px;
        }

        .item {
            padding-left: 2rem;
        }

        .item-label {
            // Để có thể hiển thị thụt vào khi thêm space đằng trước
            white-space: pre;
        }

        .check-icon {
            left: 8px;
            top: 8px;
        }

        .remove-icon {
            right: 8px;
            top: 8px;
        }
    }
</style>

<template>
    <div>
        <vue-tags-input
                v-model="tagModel"
                :tags="tags.ref"
                @tags-changed="tagsChanged"
                :placeholder="placeholder"
                :add-on-blur="false"
                :is-duplicate="checkDuplicateTag"
                :max-tags="maxTags"
                @before-adding-tag="checkAddTag"
                @input="resetError()"
                @max-tags-reached="maxTagsReached = true"/>

        <!-- Thêm -->
        <div class="validate-container pl-1">
            <span class="error-message" v-if="tagsHasDuplicate">Các phần tử không được trùng nhau</span>

            <span class="error-message" v-if="maxTagsReached">Tối đa {{maxTags}} phần tử</span>

            <span class="error-message" v-if="addMessageError">{{addMessageError}}</span>
        </div>
    </div>
</template>


<script>
import VueTagsInput from '@johmun/vue-tags-input';

export default {
    components: {
        VueTagsInput
    },

    props: {
        tags: {
            type: Object,
            default: {
                // Phải để dạng object thế này để khi thay đổi ở component thì thay đổi ở cả cha
                ref: [],
                // Chưa thay đổi (để validate real-time)
                dirty: false
            }
        },

        // Hiển thị khi chưa có tag nào
        placeholder: {
            type: String,
            default: ''
        },

        // Số tag tối đa
        maxTags: {
            type: Number,
            default: 50
        },

        // Độ dài tối thiểu của một tag
        minLength: {
            type: Number,
            default: 1
        },

        // Độ dài tối đa của một tag
        maxLength: {
            type: Number,
            default: 250
        },

        // Hàm validate khi thêm mới
        // Hàm trả về thông báo lỗi nếu có lỗi
        // hoặc '' nếu hợp lệ
        // Hàm có đầu vào là giá trị tag đang thêm
        addValidateFunction: {
            type: Function,
            default: null
        }
    },

    watch: {
        /**
             * Khi bind phần tử mới thì reset lỗi, reset trường nhập.
             */
        tags(val) {
            this.resetError();
            this.tagModel = '';
        }
    },

    data() {
        return {
            // Thẻ mới đang thêm
            tagModel: '',

            // Các thẻ có giá trị trùng hay không
            tagsHasDuplicate: false,

            // Có phải đang nhập không
            // Nếu đang nhập thì thôi không validate
            isInputing: true,

            // Thông báo lỗi khi thêm mới
            addMessageError: null,

            // Đã nhập quá số tag tối đa
            maxTagsReached: false
        };
    },

    methods: {
        /**
             * Kiểm tra thẻ có bị trùng hay không.
             * Không phân biệt hoa thường.
             */
        checkDuplicateTag(tags, tag) {
            const check = tags.map(t => t.text.toLowerCase()).indexOf(tag.text.toLowerCase()) !== -1;
            this.tagsHasDuplicate = check;
            return check;
        },

        /**
             * Khi thay đổi tag.
             */
        tagsChanged(newTags) {
            this.tags.ref = newTags;
            this.tags.dirty = true;
        },

        /**
             * Kiểm tra trước khi thêm tag.
             */
        checkAddTag(obj) {
            // Validate độ dài nhỏ nhất
            if (this.tagModel.length < this.minLength) {
                this.addMessageError = 'Ít nhất ' + this.minLength + ' ký tự';
                return;
            }

            // Validate độ dài nhỏ nhất
            if (this.tagModel.length > this.maxLength) {
                this.addMessageError = 'Nhiều nhất ' + this.maxLength + ' ký tự';
                return;
            }

            if (this.addValidateFunction) {
                const check = this.addValidateFunction(this.tagModel);
                if (check) {
                    this.addMessageError = check;
                    return;
                }
            }

            // Thêm tag
            obj.addTag();
        },

        /**
             * Không hiển thị nội dung lỗi khi đang nhập.
             */
        resetError() {
            this.addMessageError = null;
            this.maxTagsReached = false;
        }
    }
};
</script>

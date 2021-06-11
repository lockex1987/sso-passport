(() => {
    /**
     * Lắng nghe sự kiện cho một phần tử.
     */
    if (!Element.prototype.on) {
        Element.prototype.on = function (type, callback, options) {
            this.addEventListener(type, callback, options);
            return this; // for chaining
        };
    }

    /**
     * Delegate event cho các phần tử con.
     */
    if (!Element.prototype.delegate) {
        Element.prototype.delegate = function (type, selector, callback) {
            this.addEventListener(type, (evt) => {
                const target = evt.target;
                const obj = target.closest(selector);
                if (obj) {
                    callback(evt, obj);
                }
            });
            return this;
        };
    }
})();


/**
 * Nếu sử dụng từ khóa "const" ở đây
 * thì trên Safari, chỗ DownloadProgress (module script) sẽ bị lỗi không tìm thấy biến CommonUtils.
 */
const CommonUtils = {};


/**
 * Trả về một phần tử.
 * @param {String} selector CSS selector
 */
CommonUtils.$ = (selector) => {
    return document.querySelector(selector);
};


/**
 * Trả về mảng luôn để có thể thực hiện các hàm như map, reduce,...
 * @param {String} selector CSS selector
 * @param {Element} rootNode Phần tử bắt đầu
 */
CommonUtils.$$ = (selector, rootNode = document) => {
    return [...rootNode.querySelectorAll(selector)];
};


/**
 * Bắt sự kiện.
 */
CommonUtils.delegateDocument = (type, selector, callback) => {
    document.addEventListener(type, (evt) => {
        const target = evt.target;
        const obj = target.closest(selector);
        if (obj) {
            callback(evt, obj);
        }
    });
};


/**
 * Tạo một phần tử.
 * @param {String} tag Tên tag
 * @param {Object} attributes Mảng các thuộc tính cùng giá trị
 * @param {Array} children Danh sách phần tử con
 */
CommonUtils.createElement = (tag, attributes, children) => {
    const ele = document.createElement(tag);
    if (attributes) {
        for (const property in attributes) {
            if (Object.prototype.hasOwnProperty.call(attributes, property)) {
                ele[property] = attributes[property];
            }
        }
    }
    if (children && children.length > 0) {
        children.forEach(child => {
            ele.appendChild(child);
        });
    }
    return ele;
};


/**
 * Tạo một phần tử.
 * @param {Object} obj Đối tượng, có các thuộc tính như "tag", "children",...
 */
CommonUtils.create = (obj) => {
    const tag = obj.tag;
    const children = obj.children;
    delete obj.tag;
    delete obj.children;
    return CommonUtils.createElement(tag, obj, children);
};


/**
 * Lấy các tham số của form.
 * Các giá trị được encode.
 * @param {String} formId ID của form
 */
CommonUtils.getFormAsString = (formId) => {
    const formElement = document.getElementById(formId);
    return new URLSearchParams(new FormData(formElement)).toString();
};


/**
 * Chuyển đối tượng JS thành xâu tham số.
 */
CommonUtils.jsonToQueryString = (json) => {
    return '?' +
        Object.keys(json).map((key) => {
            return encodeURIComponent(key) +
                '=' +
                encodeURIComponent(json[key]);
        }).join('&');
};


/**
 * Escape các ký tự đặc biệt thành mã HTML entity tương ứng.
 * Fix bug XSS.
 * @param {String} s Xâu cần escape
 */
CommonUtils.escapeHtml = (s) => {
    if (typeof s === 'string') {
        return s.replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }
    return s;
};


/**
 * Phân cách dấu phảy phần ngàn.
 */
CommonUtils.formatThousands = (num) => {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,');
};


/**
 * Hiển thị số dạng k.
 */
CommonUtils.prettifyNumber = (num, digits) => {
    if (digits === undefined) {
        digits = 1;
    }

    const si = [
        /*
        { value: 1E18, symbol: 'E' },
        { value: 1E15, symbol: 'P' },
        */
        { value: 2 ** 40, symbol: 'T' },
        { value: 2 ** 30, symbol: 'G' },
        { value: 2 ** 20, symbol: 'M' },
        { value: 2 ** 10, symbol: 'k' }
    ];
    for (let i = 0; i < si.length; i++) {
        if (num >= si[i].value) {
            if (i <= 0) {
                digits = 3;
            } else if (i <= 1) {
                digits = 2;
            } else if (i <= 2) {
                digits = 0;
            } else {
                digits = 0;
            }
            const n = (num / si[i].value).toFixed(digits);

            // Xóa những chữ số 0 đằng sau dấu thập phân
            // Nếu chỉ để 0+ thì sẽ không xóa được dấu .
            // Nếu chỉ để \.0+ thì sẽ không xử lý được trường hợp 123.400
            // .replace(/\.?0+$/, '') sẽ bị lỗi các trường hợp như là num = 80 và digit = 0
            return parseFloat(n) + si[i].symbol;
        }
    }
    return num.toFixed(digits);
};


/**
 * Thêm các chữ số 0 ở đầu để có độ dài là 2 ký tự.
 */
CommonUtils.paddingTwoZero = (n) => {
    return (n < 10) ? ('0' + n) : n;
};


/**
 * Download dữ liệu Blob.
 */
CommonUtils.downloadBlob = (blob, fileName) => {
    const link = window.URL.createObjectURL(blob);

    // Tạo một thẻ a tạm và giả lập thao tác click vào thẻ đó
    const a = document.createElement('a');
    a.download = fileName;
    a.innerHTML = 'Download file';
    a.href = link;
    a.style.display = 'none';
    a.onclick = (evt) => {
        // Remove the a tag
        document.body.removeChild(evt.target);
    };

    // Gắn nó vào DOM và thực hiện thao tác click
    document.body.appendChild(a);
    a.click();
};


/**
 * Hàm save as do mình tự làm.
 * @param text Nội dung của văn bản cần lưu
 * @param fileName Tên file
 */
CommonUtils.saveTextAsFile = (text, fileName) => {
    // Tạo đối tượng Blob
    const textFileAsBlob = new Blob([text], { type: 'text/plain' });

    CommonUtils.downloadBlob(textFileAsBlob, fileName);
};


/**
 * Lấy tham số từ URL.
 */
CommonUtils.getUrlParameter = (param) => {
    const urlParams = new URLSearchParams(window.location.search);
    return urlParams.get(param);
};


/**
 * Không thực hiện hàm luôn khi người dùng đang thao tác,
 * mà chờ sau khi người dùng đã thực hiện xong một khoảng thời gian nào đó.
 * @param {Function} func Hàm nghiệp vụ
 * @param {Integer} delay Millisecond
 */
CommonUtils.debounce = (func, delay) => {
    let inDebounce;

    return function () {
        const context = this;
        const args = arguments;

        clearTimeout(inDebounce);
        inDebounce = setTimeout(() => {
            func.apply(context, args);
        }, delay);
    };
};


/**
 * Giới hạn số lần gọi hàm.
 * Sau khi gọi hàm thành công thì cần chờ một khoảng thời gian để gọi tiếp.
 * @param {Function} func Hàm nghiệp vụ
 * @param {Integer} limit Millisecond
 */
CommonUtils.throttle = (func, limit) => {
    let inThrottle;

    return function () {
        const args = arguments;
        const context = this;

        if (!inThrottle) {
            func.apply(context, args);
            inThrottle = true;
            setTimeout(() => {
                inThrottle = false;
            }, limit);
        }
    };
};

/**
 * Thêm CSS.
 * @param {String} styles Code CSS
 */
CommonUtils.addCssStyles = (styles) => {
    const styleSheet = document.createElement('style');
    styleSheet.type = 'text/css';
    styleSheet.innerText = styles;
    document.head.appendChild(styleSheet);
};


/**
 * Trả về một số nguyên ngẫu nhiên.
 * @param {Integer} n Số truyền vào
 * @return {Integer} Số nguyên từ 0 đến n-1
 */
CommonUtils.getRandomIndex = (n) => {
    return Math.floor(Math.random() * n);
};


/**
 * Trả về số nguyên ngẫu nhiên trong khoảng [min, max].
 * @param {Integer} min Giá trị nhỏ nhất
 * @param {Integer} max Giá trị lớn nhất
 * @returns {Integer} Số ngẫu nhiên
 */
CommonUtils.getRandomBetween = (min, max) => {
    return Math.floor(Math.random() * (max - min + 1)) + min;
};

// alert(CommonUtils);

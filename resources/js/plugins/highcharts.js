// Cấu hình highcharts
Highcharts.setOptions({
    // Tiếng Việt
    // https://api.highcharts.com/highcharts/lang
    lang: {
        // shortMonths: ['Tháng 1', 'Tháng 2', 'Tháng 3', 'Tháng 4', 'Tháng 5', 'Tháng 6', 'Tháng 7', 'Tháng 8', 'Tháng 9', 'Tháng 10', 'Tháng 11', 'Tháng 12'],
        weekdays: ['Chủ Nhật', 'Thứ Hai', 'Thứ Ba', 'Thứ Tư', 'Thứ Năm', 'Thứ Sáu', 'Thứ Bảy'],
        shortMonths: ['T1', 'T2', 'T3', 'T4', 'T5', 'T6', 'T7', 'T8', 'T9', 'T10', 'T11', 'T12'],
        decimalPoint: '.',
        thousandsSep: ',',
        viewFullscreen: 'Xem toàn màn hình',
        printChart: 'In biểu đồ',
        downloadJPEG: 'Download ảnh JPEG',
        downloadPNG: 'Download ảnh PNG',
        downloadSVG: 'Download ảnh SVG',
        downloadPDF: 'Download file PDF',
        contextButtonTitle: '' // Download biểu đồ
    },
    legend: {
        itemStyle: {
            color: '#999'
            /*
            fontWeight: 'nomal',
            fontSize: '14px'
            */
        },
        itemHoverStyle: {
            // color: '#FFF'
            color: '#AAA'
        },
        itemHiddenStyle: {
            color: '#111'
        }
    },
    credits: {
        enabled: false
    },
    global: {
        useUTC: false
    },
    chart: {
        style: {
            // Font family của Bootstrap
            fontFamily: '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji"'
        }
    },
    exporting: {
        // Mặc định không hiển thị
        // Có yêu cầu thì mới dùng
        // Có thể dùng thư viện html2canvas (https://html2canvas.hertzen.com/)
        // Thư viện html2canvas hình như bị lỗi trên iPad
        enabled: false,
        buttons: {
            contextButton: {
                // Ít tùy chọn thì ít lỗi
                //
                menuItems: ['downloadPNG', 'downloadJPEG', 'downloadPDF', 'downloadSVG']
            }
        }
    },

    navigation: {
        buttonOptions: {
            // verticalAlign: 'top',
            // y: -20,
            theme: {
                // 'stroke-width': 1,
                // stroke: 'silver',
                // r: 0,
                opacity: 0.3,
                states: {
                    hover: {
                        // fill: '#a4edba',
                        opacity: 1
                    },
                    select: {
                        // stroke: '#039',
                        // fill: '#a4edba',
                        opacity: 1
                    }
                }
            }
        }
    }
});

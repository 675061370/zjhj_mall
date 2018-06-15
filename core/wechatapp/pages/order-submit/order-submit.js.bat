// order-submit.js
var api = require('../../api.js');
var app = getApp();
var longitude = "";
var latitude = "";
Page({

    /**
     * 页面的初始数据
     */
    data: {
        total_price: 0,
        address: null,
        express_price: 0.00,
        content: '',
        offline: 0,
        express_price_1: 0.00,
        name: "",
        mobile: "",
        integral_radio: 1,
    },

    /**
     * 生命周期函数--监听页面加载
     */
    onLoad: function (options) {
        app.pageOnLoad(this);
        var page = this;
        page.setData({
            options: options,
            store: wx.getStorageSync("store")
        });
    },
    bindkeyinput: function (e) {
        this.setData({
            content: e.detail.value
        });
    },
    KeyName: function (e) {
        this.setData({
            name: e.detail.value
        });
    },
    KeyMobile: function (e) {
        this.setData({
            mobile: e.detail.value
        });
    },
    getOffline: function (e) {
        var express = this.data.express_price;
        var express_1 = this.data.express_price_1;
        var offline = e.target.dataset.index;
        if (offline == 1) {
            this.setData({
                offline: offline,
                express_price: 0,
                express_price_1: express
            });
        } else {
            this.setData({
                offline: offline,
                express_price: express_1
            });
        }
    },
    dingwei: function () {
        var page = this;
        wx.chooseLocation({
            success: function (e) {
                longitude = e.longitude;
                latitude = e.latitude;
                page.setData({
                    location: e.address,
                });
            },
            fail: function (res) {
                app.getauth({
                    content: "需要获取您的地理位置授权，请到小程序设置中打开授权",
                    success: function (e) {
                        if (e) {
                            if (e.authSetting["scope.userLocation"]) {
                                page.dingwei();
                            } else {
                                wx.showToast({
                                    title: '您取消了授权',
                                    image: '/images/icon-warning.png'
                                })
                            }
                        }
                    }
                });
            }
        })
    },

    orderSubmit: function () {
        var page = this;
        var offline = page.data.offline;
        var data = {};
        if (offline == 0) {
            if (!page.data.address || !page.data.address.id) {
                wx.showToast({
                    title: "请选择收货地址",
                    image: "/images/icon-warning.png",
                });
                return;
            }
            data.address_id = page.data.address.id;
        } else {
            data.address_name = page.data.name;
            data.address_mobile = page.data.mobile;
            if (page.data.shop.id) {
                data.shop_id = page.data.shop.id;
            }
            if (!data.address_name || data.address_name == undefined) {
                wx.showToast({
                    title: "请填写收货人",
                    image: "/images/icon-warning.png",
                });
                return ;
            }
            if (!data.address_mobile || data.address_mobile == undefined) {
                wx.showToast({
                    title: "请填写联系方式",
                    image: "/images/icon-warning.png",
                });
                return;
            }
        }
        data.offline = offline;
        if (page.data.cart_id_list) {
            data.cart_id_list = JSON.stringify(page.data.cart_id_list);
        }
        if (page.data.goods_info) {
            data.goods_info = JSON.stringify(page.data.goods_info);
        }
        if (page.data.picker_coupon) {
            data.user_coupon_id = page.data.picker_coupon.user_coupon_id;
        }
        if (page.data.content) {
            data.content = page.data.content
        }
        wx.showLoading({
            title: "正在提交",
            mask: true,
        });
        page.data.integral_radio == 1 ? data.use_integral = 1 : data.use_integral = 2;

        //提交订单
        app.request({
            url: api.order.submit,
            method: "post",
            data: data,
            success: function (res) {
                if (res.code == 0) {
                    setTimeout(function () {
                        wx.hideLoading();
                    }, 1000);
                    setTimeout(function () {
                        page.setData({
                            options: {},
                        });
                    }, 1);
                    var order_id = res.data.order_id;

                    //获取支付数据
                    app.request({
                        url: api.order.pay_data,
                        data: {
                            order_id: order_id,
                            pay_type: 'WECHAT_PAY',
                        },
                        success: function (res) {
                            if (res.code == 0) {
                                //发起支付
                                wx.requestPayment({
                                    timeStamp: res.data.timeStamp,
                                    nonceStr: res.data.nonceStr,
                                    package: res.data.package,
                                    signType: res.data.signType,
                                    paySign: res.data.paySign,
                                    success: function (e) {
                                        wx.redirectTo({
                                            url: "/pages/order/order?status=1",
                                        });
                                    },
                                    fail: function (e) {
                                    },
                                    complete: function (e) {
                                        if (e.errMsg == "requestPayment:fail" || e.errMsg == "requestPayment:fail cancel") {//支付失败转到待支付订单列表
                                            wx.showModal({
                                                title: "提示",
                                                content: "订单尚未支付",
                                                showCancel: false,
                                                confirmText: "确认",
                                                success: function (res) {
                                                    if (res.confirm) {
                                                        wx.redirectTo({
                                                            url: "/pages/order/order?status=0",
                                                        });
                                                    }
                                                }
                                            });
                                            return;
                                        }
                                        if (e.errMsg == "requestPayment:ok") {
                                            return;
                                        }
                                        wx.redirectTo({
                                            url: "/pages/order/order?status=-1",
                                        });
                                    },
                                });
                                return;
                            }
                            if (res.code == 1) {
                                wx.showToast({
                                    title: res.msg,
                                    image: "/images/icon-warning.png",
                                });
                                return;
                            }
                        }
                    });
                }
                if (res.code == 1) {
                    wx.hideLoading();
                    wx.showToast({
                        title: res.msg,
                        image: "/images/icon-warning.png",
                    });
                    return;
                }
            }
        });
    },

    /**
     * 生命周期函数--监听页面初次渲染完成
     */
    onReady: function () {

    },

    /**
     * 生命周期函数--监听页面显示
     */
    onShow: function () {
        var page = this;
        var address = wx.getStorageSync("picker_address");
        if (address) {
            page.setData({
                address: address,
                name: address.name,
                mobile: address.mobile
            });
            wx.removeStorageSync("picker_address");
        }
        page.getOrderData(page.data.options);
    },

    getOrderData: function (options) {
        var page = this;
        var address_id = "";
        if (page.data.address && page.data.address.id)
            address_id = page.data.address.id;
        if (options.cart_id_list) {
            var cart_id_list = JSON.parse(options.cart_id_list);
            wx.showLoading({
                title: "正在加载",
                mask: true,
            });
            app.request({
                url: api.order.submit_preview,
                data: {
                    cart_id_list: options.cart_id_list,
                    address_id: address_id,
                    longitude: longitude,
                    latitude: latitude
                },
                success: function (res) {
                    wx.hideLoading();
                    if (res.code == 0) {
                        var total_price_1 = res.data.total_price - res.data.integral.forehead;
                        page.setData({
                            total_price: parseFloat(res.data.total_price),
                            goods_list: res.data.list,
                            cart_id_list: res.data.cart_id_list,
                            address: res.data.address,
                            express_price: parseFloat(res.data.express_price),
                            coupon_list: res.data.coupon_list,
                            shop_list: res.data.shop_list,
                            shop: res.data.shop_list[0] || {},
                            name: res.data.address ? res.data.address.name : '',
                            mobile: res.data.address ? res.data.address.mobile : '',
                            send_type: res.data.send_type,
                            level: res.data.level,
                            total_price_1: parseFloat(total_price_1),
                            integral: res.data.integral,
                        });
                        if (res.data.send_type == 1) {//仅快递
                            page.setData({
                                offline: 0,
                            });
                        }
                        if (res.data.send_type == 2) {//仅自提
                            page.setData({
                                offline: 1,
                            });
                        }
                        if (res.data.level) {
                            page.setData({
                                total_price_1: parseFloat((total_price_1 * res.data.level.discount / 10).toFixed(2))
                            });
                        }
                    }
                    if (res.code == 1) {
                        wx.showModal({
                            title: "提示",
                            content: res.msg,
                            showCancel: false,
                            confirmText: "返回",
                            success: function (res) {
                                if (res.confirm) {
                                    wx.navigateBack({
                                        delta: 1,
                                    });
                                }
                            }
                        });
                    }
                }
            });
        }
        if (options.goods_info) {
            wx.showLoading({
                title: "正在加载",
                mask: true,
            });
            app.request({
                url: api.order.submit_preview,
                data: {
                    goods_info: options.goods_info,
                    address_id: address_id,
                    longitude: longitude,
                    latitude: latitude
                },
                success: function (res) {
                    wx.hideLoading();
                    if (res.code == 0) {
                        var total_price_1 = res.data.total_price - res.data.integral.forehead;
                        page.setData({
                            total_price: res.data.total_price,
                            goods_list: res.data.list,
                            goods_info: res.data.goods_info,
                            address: res.data.address,
                            express_price: parseFloat(res.data.express_price),
                            coupon_list: res.data.coupon_list,
                            shop_list: res.data.shop_list,
                            shop: res.data.shop_list[0] || {},
                            name: res.data.address ? res.data.address.name : '',
                            mobile: res.data.address ? res.data.address.mobile : '',
                            send_type: res.data.send_type,
                            level: res.data.level,
                            total_price_1: parseFloat(total_price_1),
                            integral: res.data.integral,
                        });
                        if (res.data.send_type == 1) {//仅快递
                            page.setData({
                                offline: 0,
                            });
                        }
                        if (res.data.send_type == 2) {//仅自提
                            page.setData({
                                offline: 1,
                            });
                        }
                        if (res.data.level) {
                            page.setData({
                                total_price_1: parseFloat((total_price_1 * res.data.level.discount / 10).toFixed(2))
                            });
                        }
                    }
                    if (res.code == 1) {
                        wx.showModal({
                            title: "提示",
                            content: res.msg,
                            showCancel: false,
                            confirmText: "返回",
                            success: function (res) {
                                if (res.confirm) {
                                    wx.navigateBack({
                                        delta: 1,
                                    });
                                }
                            }
                        });
                    }
                }
            });
        }
    },

    copyText: function (e) {
        var text = e.currentTarget.dataset.text;
        if (!text)
            return;
        wx.setClipboardData({
            data: text,
            success: function () {
                wx.showToast({
                    title: "已复制内容",
                });
            },
            fail: function () {
                wx.showToast({
                    title: "复制失败",
                    image: "/images/icon-warning.png",
                });
            },
        });
    },

    showCouponPicker: function () {
        var page = this;
        if (page.data.coupon_list && page.data.coupon_list.length > 0) {
            page.setData({
                show_coupon_picker: true,
            });
        }
    },

    pickCoupon: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        if (index == '-1' || index == -1) {
            page.setData({
                picker_coupon: false,
                show_coupon_picker: false,
            });
        } else {
            var new_total_price = page.data.total_price - page.data.coupon_list[index].sub_price - page.data.integral.forehead;
            if (page.data.level) {
                new_total_price = new_total_price * page.data.level.discount / 10;
            }
            page.setData({
                picker_coupon: page.data.coupon_list[index],
                show_coupon_picker: false,
                new_total_price: parseFloat(new_total_price.toFixed(2)),
            });
        }
    },

    numSub: function (num1, num2, length) {
        return 100;
    },
    showShop: function (e) {
        var page = this;
        page.dingwei();
        if (page.data.shop_list && page.data.shop_list.length > 1) {
            page.setData({
                show_shop: true,
            });
        }
    },
    pickShop: function (e) {
        var page = this;
        var index = e.currentTarget.dataset.index;
        if (index == '-1' || index == -1) {
            page.setData({
                shop: false,
                show_shop: false,
            });
        } else {
            page.setData({
                shop: page.data.shop_list[index],
                show_shop: false,
            });
        }
    },
    // integralRadio:function(e){
    //     var page = this;
    //     var index = e.currentTarget.dataset.index;
    //     if (index == null || index =='radio'){
    //         page.setData({
    //             integral_radio: 'radio-active',
    //         });
    //     } else {
    //         page.setData({
    //             integral_radio: 'radio',
    //         });
    //     }
    // },
    integralSwitchChange:function(e){
        var page = this;
        var discount = page.data.level.discount / 10;

        if(e.detail.value != false){
            page.setData({
                integral_radio: 1,
                total_price_1: parseFloat(((page.data.total_price_1 / discount - parseFloat(page.data.integral.forehead)) * discount).toFixed(2)),
            });
        }else{
            var discount1 = page.data.total_price_1 / discount;
            page.setData({
                integral_radio: 2,
                total_price_1: parseFloat(((parseFloat(page.data.integral.forehead) + discount1)* discount).toFixed(2)),
            });
        }
    },
    integration: function(e){
        var page = this;
        var integration = page.data.integral.integration;
        wx.showModal({
            title: '积分使用规则',
            content: integration,
            showCancel:false,
            confirmText:'我知道了',
            confirmColor:'#ff4544',
            success: function (res) {
                if (res.confirm) {
                    console.log('用户点击确定')
                } 
            }
        });
    },

});
export function Model (product_number='', product_img='', product_name='', product_amount= 0, remain_amount= 0, order_id= null, buy_date = new Date(),  remark='') {
    this.product_number = product_number
    this.product_img = product_img
    this.product_name = product_name
    this.product_amount = product_amount
    this.remain_amount = remain_amount
    this.order_id = order_id
    this.buy_date = buy_date
    this.remark = remark
}

export function SearchModel( product_number='', product_name='') {
   this.product_number = product_number
   this.product_name = product_name
}

let Rules = {
  product_number: [
    { required: true, message: '请填写产品编号', trigger: 'blur' },
  ],
  product_amount: [
    { required: true, message: '请填写产品数量', trigger: 'blur' },
  ],
}

export {Rules}

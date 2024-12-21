export function Model (package_id= null , order_id = null,product_id = null,product_amount = null,remark='') {
    this.package_id = package_id
    this.order_id = order_id
    this.product_id = product_id
    this.product_amount = product_amount
    this.remark = remark
}

export function SearchModel( logistics_number ='', logistics_date = null) {
   this.logistics_number = logistics_number
   this.logistics_date = logistics_date
}

let Rules = {
  logistics_number: [
    { required: true, message: '请填写订单编号', trigger: 'blur' },
  ],
  plogistics_date: [
    { required: true, message: '请填写订单日期', trigger: 'blur' },
  ],
}

export {Rules}

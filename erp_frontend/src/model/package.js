export function Model (logistics_number='', logistics_date= new Date(), items = [], remark='') {
    this.logistics_number = logistics_number
    this.logistics_date = logistics_date
    this.items = items
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
  logistics_date: [
    { required: true, message: '请填写订单日期', trigger: 'blur' },
  ],
}

export {Rules}

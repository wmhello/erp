export function Model (good_id= null, config_number='',good_type='基本商品',good_number='', good_count = null, date = null,remark='') {
    this.good_id = good_id
    this.good_type="基本商品"
    this.config_number = config_number
    this.good_number = good_number
    this.good_count = good_count
    this.date = date
    this.remark = remark
}

export function SearchModel(good_number= '', date = null) {
   this.good_number = good_number
   this.date  = date
}

let Rules = {

  date:[
    {required: true, message: '请选择时间', trigger: 'blur'}
  ],
  good_count: [
    { required: true, message: '请设置销售数量', trigger: 'blur' },
  ],
}

export {Rules}

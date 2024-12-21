export function Model (good_id= null, good_number='', good_count = null, date = null,remark='') {
    this.good_id = good_id
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
  good_number: [
    { required: true, message: '请选择入库商品', trigger: 'blur' },
  ],
  good_count: [
    { required: true, message: '请设置入库数量', trigger: 'blur' },
  ],
  date: [
  { type:"string", required: true, message: '请选择日期', trigger: 'change' }
  ]

}

export {Rules}

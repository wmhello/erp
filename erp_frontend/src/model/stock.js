export function Model (good_id= null, good_number='',good_name='',count=null,remark='') {
    this.good_id = good_id
    this.good_number = good_number
    this.good_name = good_name
    this.count = count
    this.remark = remark
}

export function SearchModel(good_number= '', cate_id = null) {
   this.good_number = good_number
   this.cate_id = cate_id
}

let Rules = {
  good_number: [
    { required: true, message: '请选择商品', trigger: 'blur' },
  ],
}

export {Rules}

export function Model (desc='', value='', range = null, remark='') {
    this.desc = desc
    this.value = value
    this.range = range
    this.remark = remark
}

export function SearchModel(good_number = '', cate_id = null) {
   this.good_number = good_number,
   this.cate_id = cate_id
}

let Rules = {
  desc: [
    { required: true, message: '请填写算法名称', trigger: 'blur' },
  ],
  value: [
    { required: true, message: '请填写算法的计算方式', trigger: 'blur' },
  ],
}

export {Rules}

export function Model (name='', number='', source='', rule_color='', rule_size = '',img = '', cate_id = null, cost = null, remark='') {
    this.name = name
    this.number =number
    this.source =source
    this.img =img
    this.rule_color =rule_color
    this.rule_size =rule_size
    this.cate_id =cate_id
    this.cost =cost
    this.remark = remark
}

export function SearchModel(number = null, cate_id = null) {
   this.number = number
   this.cate_id = cate_id
}

let Rules = {
  number: [
    { required: true, message: '请填写商品编号', trigger: 'blur' },
  ],
}

export {Rules}

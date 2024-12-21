export function Model (name='', desc='', value='', remark='') {
    this.name = name
    this.desc = desc
    this.value = value
    this.remark = remark
}

export function SearchModel(name= '') {
   this.name = name
}

let Rules = {
  name: [
    { required: true, message: '请填写参数名称(英文)', trigger: 'blur' },
  ],
  desc: [
    { required: true, message: '请填写参数说明(中文)', trigger: 'blur' },
  ],

  value: [
    { required: true, message: '请设置参数值', trigger: 'blur' },
  ],
}

export {Rules}

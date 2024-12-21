export function Model (name='', pid=0, remark='') {
    this.name = name
    this.pid = pid
    this.remark = remark
}

export function SearchModel(name= '') {
   this.name = name
}

let Rules = {
  name: [
    { required: true, message: '请填写物品种类', trigger: 'blur' },
  ],
}

export {Rules}

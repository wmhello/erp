export function Model (sub_number = '', result=[],remark='') {
    this.sub_number = sub_number
    this.result = [];
    this.remark = remark
}

export function Single(id=null,number=null, name='',count=null, status =1){
   this.id = null,
   this.number = number
   this.name = name
   this.count = count
   this.status = status
}

export function SearchModel(sub_number= null, good_number = null) {
   this.sub_number = sub_number
   this.good_number = good_number
}

let Rules = {
  sub_number: [
    { required: true, message: '请填写配对编号', trigger: 'blur' },
  ],
}

export {Rules}

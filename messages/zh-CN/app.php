<?php
/**
 * Message translations.
 *
 * This file is automatically generated by 'yii message' command.
 * It contains the localizable messages extracted from source code.
 * You may modify this file by translating the extracted messages.
 *
 * Each array element represents the translation (value) of a message (key).
 * If the value is empty, the message is considered as not translated.
 * Messages that no longer need translation will have their translations
 * enclosed between a pair of '@@' marks.
 *
 * Message string can be used with plural forms format. Check i18n section
 * of the guide for details.
 *
 * NOTE: this file must be saved in UTF-8 encoding.
 */
return [
    //Common
    'Action' => '操作',
    'Change' => '修改',
    'Save' => '保存',
    'Create' => '创建',
    'Update' => '更新',
    'Processed {count} records successfully.' => '成功保存{count}条记录！',
    'Normal' => '正常',
    'Deleted' => '已删除',
    'Finished' => '已完成',
    'Closed' => '关闭',
    'Export table' => '导出表格',
    
    
    //Main page
    'Welcome to ' => '欢迎使用',
    'Geely Auto (Shanghai) manhour system' => '吉利汽车（上海）工时管理系统',
    'Geely Auto (Shanghai) ' => '吉利汽车（上海）',
    'Home' => '首页',
    'Manhour' => '工时记录',
    'User' => '用户管理',
    'Project' =>'项目管理',
    'Export' =>'工时导出',
    'Management' => '系统管理',
    'Supplier' => '供应商管理',
    'Department' => '吉利部门管理',
    'Setting' => '系统设置',
    'Update personal information' => '更新个人信息',
    'Change password' => '修改密码',
    'Sync attendance' => '同步考勤数据',
    
    
    //Setting page
    'In how many days can modify entry logs' => '可以录入\修改多少天内的工时记录',
    'Initial user password' => '初始用户密码',
    'Reset to default' => '恢复默认值',
    'Attendance DB sync start AutoID' => '考勤数据库同步起始AutoID',
    'Attendance DB last sync time' => '考勤数据库最近同步时间',
    'Attendance DB name' => '考勤数据库名称',
    'Attendance DB password' => '考勤数据库密码',
    'Attendance DB server' => '考勤数据库服务器地址',
    'Attendance DB username' => '考勤数据库用户名',
       
    //Login page
    'Login' => '登录',
    'Logout' => '登出',
    'Remember me' => '记住我',
    'Enter username...' => '请输入用户名..',
    'Enter password...' => '请输入密码..',
    'Incorrect username or password!' => '用户名或密码不正确！',
    
    //User index page
    'Working hour statistics in one week' => '一周工时统计图表',
    'Working project number' => '当天工作的项目总数',
    'Working time(Hour)' => '工作时间(小时)',
    
    //Entry export
    'Project time cost statistics' => '项目时间花费统计',
    'Time cost(Hour)' => '时间消耗（小时）',
    'Number of days/users' => '天数/人员数',
    'Time cost' => '总时间消耗',
    'User number working in this project' => '项目中工作人员数',
    'Project lasting days' => '项目持续天数',
    'Start date' => '起始日期',
    'End date' => '截止日期',
    'Generate report' => '生成报表',
    'Reset' => '重置',
    '(Multiple selection)' => '(多选)',
    '(Single selection)' => '(单选)',
    
    //User table
    'User ID' => '用户ID',
    'User type' => '用户类型',
    'Username' => '用户名',
    'Personal name' => '姓名',
    'Identity card' => '身份证',
    'Employe ID(To sync attendence DB)' => '员工号(用于同步考勤数据库)',
    'Department ID' => '部门号',
    'Geely department' => '吉利部门',
    'Supplier name' => '供应商名称',
    'Mobile' => '手机号',
    'Email' => '邮箱帐号',
    'Experience(Years)' => '工作经验(年)',
    'Password' => '密码',    
    'Created time' => '创建时间',
    'Created by' => '创建者',
    'Last updated time' => '更新时间',
    'Last updated by' => '更新者',
    'User list' => '用户列表',
    'Add new user' => '添加新用户',
    'Modify user information' =>'修改用户信息',
    'Reset password' => '重置密码',
    'Batch delete users' => '批量删除用户',
    'Batch reset password' => '批量重置密码',
    'Synchronize user list' => '同步用户列表',
    
    'Old password' => '旧密码',
    'New password' => '新密码',
    'Confirm password' => '确认密码',
    'Is not same as New passowrd!' => '确认密码与新密码不一致!',
    'Change password successfully!' => '修改密码成功！',
    'Change password failed!' => '修改密码失败！',
    'Please select at least one row to reset password!' => '请选择需要重置密码的用户！',
    'Please confirm to reset password of the selected rows?' => '请确认您要重置这些用户的密码？',
    
    'Uninitialized'   => '未启用用户',
    'Super admin'     => '超级管理员',
    'Administrator'   => '管理员',
    'Normal user'     => '普通员工',
    'Closed user'     => '已禁用员工',
    
    
    
    //Department table
    'Geely departments' => '吉利部门列表',
    'Geely department name' => '吉利部门名称',
    'Add new department' => '添加新部门',    

    //Vendor table
    'Suppliers' => '供应商列表',
    'Supplier company name' => '供应商公司名称',
    'Add new supplier' => '添加新供应商',
    
    //Project table
    'Project ID' => '项目号',
    'State' => '状态',
    'Project name' => '项目名称',
    'Start date' => '启动日期',
    'Target date' => '预计完成日期',
    'Finish date' => '完成日期',
    'Project description' => '项目描述',
    '+Add new project' => '+添加新项目',
    'Batch delete projects' => '批量删除项目',    
    'Modify project information' =>'修改项目内容',
    'Project list' => '项目列表',
    'Working project' => '任务所属项目',
    'Color' => '颜色',
    'Choose' => '选择',
    'Cancel' => '取消',
    'Add default children projects' => '添加默认子项目树',

    //Entry table
    'Manhour log ID' => '工时记录ID',
    'Manhour logs' => '工时记录列表',    
    'Date' => '日期',
    'Start' => '开始',
    'Duration' => '时长',
    'End' => '结束',
    'Card start time' => '打卡开始时间',
    'Card end time' => '打卡结束时间',
    'Working description' => '工作内容描述',
    'Last updated by user name' => '更新人员姓名',
    'Modify manhour log' =>'修改工时记录',
        
    'Add a manhour log'   => '登记新工时',
    'Batch delete logs' => '批量删除记录',    
    'Quick adding desciption' => '快捷插入项目描述',
    'Time overlaps other log!' => '时间与其它记录有重叠！',
    'No data' => '无',
    'Current max AutoID of attendance DB' => '当前考勤数据库最大AutoID',
    'Attendance DB last sync AutoID' => '最后同步的AutoID',
    
    //Export
    'Manhour statistics by person & month' => '费用结算明细',
    'Year' => '年份',
    'Working hours per month' => '',
    'Price/Hour' => '单价(每小时)',
    'Working hours' => '合计/工作小时',
    'Cost summary' => '费用合计人民币',
    'Remark' => '备注',
    
    'Attendance table' => '员工考勤表',
    
    'Export manhour cost by month' => '导出按月工时表',
    'Export attendance by person' => '导出人员考勤表',
    'Export generally' => '通用导出',
    
    //Other
    'Add New' => '添加',
    'Delete' => '删除',    
    'Please choose...' => '请选择...',
    'Please select at least one row to delete!' => '您还没有选择要删除的行！',
    'Please confirm to delete the selected rows?' => '请确认您要删除这些行数据？',
    'Save successfully!' => '保存成功！',
    'Save unsuccessfully!' => '保存失败！',
    'Delete successfully!' => '删除成功！',
    'Delete unsuccessfully!' => '删除失败！',    
    //'{attribute} must be no greater than {max}.' => '{attribute}的值必须不大于{max}。',
    //'{delta, plural, =1{an hour} other{# hours}} ago' => '{delta}小时前',
    
    //Months
    "Jan" => '1月', 
    "Feb" => '2月', 
    "Mar" => '3月', 
    "Apr" => '4月', 
    "May" => '5月', 
    "Jun" => '6月', 
    "Jul" => '7月', 
    "Aug" => '8月', 
    "Sep" => '9月', 
    "Oct" => '10月', 
    "Nov" => '11月', 
    "Dec" => '12月',
];

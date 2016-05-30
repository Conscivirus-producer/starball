<?php
/**
 * Created by PhpStorm.
 * User: junnanding
 * Date: 16/3/30
 * Time: 下午1:59
 */
namespace Common\Logic;
use Common\Model\OrderBillModel;
class OrderBillLogic extends OrderBillModel{
	public function createBill($data){
		$data['createdDate'] = date("Y-m-d H:i:s" ,time());
		$data['lastUpdatedDate'] = date("Y-m-d H:i:s" ,time());
		$this->add($data);
	}
	
	public function update($data){
		$data['lastUpdatedDate'] = date("Y-m-d H:i:s" ,time());
		$this->where('billId='.$data['billId'])->save($data);
	}
	
	public function findOrderSuccessPayBill($orderNumber){
		$map['orderNumber'] = $orderNumber;
		$map['type'] = 'PAY';
		$map['status'] = 'S';
		return $this->where($map)->order('createdDate desc')->find();
	}
	
	public function queryBill($map){
		return $this->where($map)->order('createdDate desc')->select();
	}
	
	public function findPayBill($billNumber){
		$map['billNumber'] = $billNumber;
		$map['type'] = 'PAY';
		return $this->where($map)->find(); 
	}
	
	public function findRefundBill($billNumber){
		$map['billNumber'] = $billNumber;
		$map['type'] = 'REFUND';
		return $this->where($map)->find(); 
	}
}
<?php
if(!isset($lang->purchase)) $lang->purchase = new stdclass();
$lang->purchase->common      = 'Purchase';
$lang->purchase->id          = 'ID';
$lang->purchase->code        = 'Code';
$lang->purchase->provider    = 'Provider';
$lang->purchase->status      = 'Status';
$lang->purchase->totalAmount = 'Total';
$lang->purchase->currency    = 'Currency';
$lang->purchase->purchaser   = 'Purchaser';
$lang->purchase->orderDate   = 'Order Date';
$lang->purchase->receiveDate = 'ETA';
$lang->purchase->reviewedBy  = 'Reviewed By';
$lang->purchase->reviewedDate = 'Reviewed Date';
$lang->purchase->description = 'Description';
$lang->purchase->createdBy   = 'Created By';
$lang->purchase->createdDate = 'Created Date';
$lang->purchase->editedBy    = 'Edited By';
$lang->purchase->editedDate  = 'Edited Date';

$lang->purchase->index   = 'Purchases';
$lang->purchase->browse  = 'Purchases';
$lang->purchase->create  = 'Create Purchase';
$lang->purchase->edit    = 'Edit Purchase';
$lang->purchase->view    = 'Purchase Detail';
$lang->purchase->submit  = 'Submit';
$lang->purchase->approve = 'Approve';
$lang->purchase->reject  = 'Reject';
$lang->purchase->receive = 'Receive';
$lang->purchase->delete  = 'Delete';

$lang->purchase->productName = 'Product';
$lang->purchase->spec        = 'Spec';
$lang->purchase->unit        = 'Unit';
$lang->purchase->quantity    = 'Qty';
$lang->purchase->price       = 'Price';
$lang->purchase->amount      = 'Amount';
$lang->purchase->receivedQty = 'Received';
$lang->purchase->remainQty   = 'Remaining';

$lang->purchase->submitSuccess  = 'Submitted';
$lang->purchase->approveSuccess = 'Approved';
$lang->purchase->rejectSuccess  = 'Rejected';
$lang->purchase->receiveSuccess = 'Received';
$lang->purchase->errorDeleteNotDraft = 'Only draft purchases can be deleted';

$lang->purchase->statusList['draft']  = 'Draft';
$lang->purchase->statusList['wait']   = 'Pending';
$lang->purchase->statusList['pass']   = 'Approved';
$lang->purchase->statusList['reject'] = 'Rejected';
$lang->purchase->statusList['closed'] = 'Closed';

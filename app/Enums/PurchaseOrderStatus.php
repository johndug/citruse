<?php

namespace App\Enums;

enum PurchaseOrderStatus: string
{
    case NEW = 'new';
    case ACCEPTED_BY_SUPPLIER = 'accepted_by_supplier';
    case IN_DELIVERY = 'in_delivery';
    case DELIVERED = 'delivered';
    case REJECTED_BY_SUPPLIER = 'rejected_by_supplier';
    case REJECTED_BY_DISTRIBUTER = 'rejected_by_distributer';
    case CANCELLED = 'cancelled';
}

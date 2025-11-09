<?php
namespace FaganChalabizada\Epoint\Enums;

enum PaymentStatus: string
{
    case UNDEFINED = 'UNDEFINED';
    case NEW = 'new';
    case SUCCESS = 'success';
    case FAILED = 'failed';
    case RETURNED = 'returned';
    case SERVER_ERROR = 'server_error';
}
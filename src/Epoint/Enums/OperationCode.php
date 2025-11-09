<?php
namespace FaganChalabizada\Epoint\Enums;

enum OperationCode: string
{
    case UNDEFINED = 'UNDEFINED';
    case CARD_REGISTRATION = '001';
    case USER_PAYMENT = '100';
    case CARD_REGISTRATION_WITH_PAYMENT = '200';
}

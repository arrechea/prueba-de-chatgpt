<?php
/**
 * Created by IntelliJ IDEA.
 * User: MEISA
 * Date: 08/03/2018
 * Time: 11:29 AM
 */

namespace App\Librerias\Permissions;

abstract class LibListPermissions
{
    /*
     * Menus
     */
    const MENU_COMPANIES = 'menu.companies';
    const MENU_USERS = 'menu.users';
    const MENU_ADMINISTRATORS = 'menu.administrators';
    const MENU_SETTINGS = 'menu.settings';
    const MENU_SYSTEM_LOG = 'menu.system_log';

    const MENU_METRICS = 'menu.metrics';
    const MENU_BUSINESS_INTELLIGENCE = 'menu.business_intelligence';
    const MENU_MEETINGS = 'menu.meetings';
    const MENU_MARKETING = 'menu.marketing';
    const MENU_SERVICES = 'menu.services';
    const MENU_CREDITS = 'menu.credits';
    const MENU_CREDITSGF = 'menu.creditsgf';
    const MENU_DISCOUNT = 'menu.discount';
    const MENU_ROLES = 'menu.roles';

    const MENU_BRANDS = 'menu.brands';
    const MENU_STUDIES = 'menu.studies';
    const MENU_RESERVATIONS = 'menu.reservations';
    const MENU_CALENDAR = 'menu.calendar';
    const MENU_STORE = 'menu.store';
    const MENU_STAFF = 'menu.staff';
    const MENU_ADMINISTRATION = 'menu.administration';
    const MENU_ROOMS = 'menu.rooms';
    const MENU_MAILS = 'menu.mails';
    const MENU_PURCHASE = 'menu.purchase';
    const MENU_PAYMENTS = 'menu.payments';
    const MENU_CATALOGS = 'menu.catalogs';
    const MENU_PRODUCTS = 'menu.products';

    /*
     * COMPANIES
     */
    const COMPANY_VIEW = 'companies.view';
    const COMPANY_EDIT = 'companies.edit';
    const COMPANY_CREATE = 'companies.create';
    const COMPANY_DELETE = 'companies.delete';
    /*
     * USERS
     */
    const USER_VIEW = 'users.view';
    const USER_EDIT = 'users.edit';
    const USER_CREATE = 'users.create';
    const USER_DELETE = 'users.delete';
    const USER_VERIFY = 'users.verify';
    const USER_UNBLOCK = 'users.unblock';
    const USER_BLOCK = 'users.block';

    /*
     * MAILS
     */
    const MAILS_VIEW = 'mails.view';
    const MAILS_EDIT = 'mails.edit';
    const MAILS_CREATE = 'mails.create';
    const MAILS_DELETE = 'mails.delete';

    /*
     * Admins
     */
    const ADMIN_VIEW = 'admins.view';
    const ADMIN_EDIT = 'admins.edit';
    const ADMIN_CREATE = 'admins.create';
    const ADMIN_DELETE = 'admins.delete';
    const ADMIN_ASIGN_ROLES = 'admins.assign';

    /*
     * ROLES
     */
    const ROLES_VIEW = 'roles.view';
    const ROLES_EDIT = 'roles.edit';
    const ROLES_CREATE = 'roles.create';
    const ROLES_DELETE = 'roles.delete';
    /*
     * SETTINGS
     */
    const SETTINGS_VIEW = 'settings.view';
    const SETTINGS_EDIT = 'settings.edit';
    const SETTINGS_DELETE = 'settings.delete';

    /*
     * Brands
     */
    const BRANDS_VIEW = 'brands.view';
    const BRANDS_EDIT = 'brands.edit';
    const BRANDS_CREATE = 'brands.create';
    const BRANDS_DELETE = 'brands.delete';

    /*
     * Metrics
     */
    const METRICS_VIEW = 'metrics.view';
    const METRICS_EDIT = 'metrics.edit';
    const METRICS_CREATE = 'metrics.create';
    const METRICS_DELETE = 'metrics.delete';
    const EXPORT_METRICS = 'metrics.export';
    /*
     * Marketing
     */
    const MARKETING_VIEW = 'marketing.view';
    const MARKETING_EDIT = 'marketing.edit';
    const MARKETING_CREATE = 'marketing.create';
    const MARKETING_DELETE = 'marketing.delete';

    /*
     *Credits
     */
    const CREDITS_VIEW = 'credits.view';
    const CREDITS_EDIT = 'credits.edit';
    const CREDITS_CREATE = 'credits.create';
    const CREDITS_DELETE = 'credits.delete';

    /*
     * Company Credits
     */

    const CREDITSCOMPANY_VIEW = 'credits_company.view';
    const CREDITSCOMPANY_EDIT = 'credits_company.edit';
    const CREDITSCOMPANY_CREATE = 'credits_company.create';
    const CREDITSCOMPANY_DELETE = 'credits_company.delete';

    /*
    *Credits GafaFit
    */
    const CREDITSGF_VIEW = 'credits_gf.view';
    const CREDITSGF_EDIT = 'credits_gf.edit';
    const CREDITSGF_CREATE = 'credits_gf.create';
    const CREDITSGF_DELETE = 'credits_gf.delete';

    /*
     * Services
     */
    const SERVICES_VIEW = 'services.view';
    const SERVICES_EDIT = 'services.edit';
    const SERVICES_CREATE = 'services.create';
    const SERVICES_DELETE = 'services.delete';
    /*
    * Service Special Texts
    */
    const SERVICE_SPECIAL_TEXTS_EDIT = 'service_special_texts.edit';
    const SERVICE_SPECIAL_TEXTS_CREATE = 'service_special_texts.create';
    const SERVICE_SPECIAL_TEXTS_DELETE = 'service_special_texts.delete';

    /*
     * Locations
     */
    const  LOCATION_VIEW = 'location.view';
    const LOCATION_EDIT = 'location.edit';
    const LOCATION_CREATE = 'location.create';
    const LOCATION_DELETE = 'location.delete';

    /*
     * Offers
     */
    const  OFFER_VIEW = 'offer.view';
    const OFFER_EDIT = 'offer.edit';
    const OFFER_CREATE = 'offer.create';
    const OFFER_DELETE = 'offer.delete';
    /*
     * Combos
     */
    const COMBOS_VIEW = 'combos.view';
    const COMBOS_EDIT = 'combos.edit';
    const COMBOS_CREATE = 'combos.create';
    const COMBOS_DELETE = 'combos.delete';

    /*
     * Rooms
     */
    const ROOMS_VIEW = 'rooms.view';
    const ROOMS_EDIT = 'rooms.edit';
    const ROOMS_CREATE = 'rooms.create';
    const ROOMS_DELETE = 'rooms.delete';

    /*
     * Staff
     */
    const STAFF_VIEW = 'staff.view';
    const STAFF_EDIT = 'staff.edit';
    const STAFF_CREATE = 'staff.create';
    const STAFF_DELETE = 'staff.delete';
    const STAFF_MENU = 'staff.menu';
    /*
     * Staff Special Texts
     */
    const STAFF_SPECIAL_TEXTS_EDIT = 'staff_special_texts.edit';
    const STAFF_SPECIAL_TEXTS_CREATE = 'staff_special_texts.create';
    const STAFF_SPECIAL_TEXTS_DELETE = 'staff_special_texts.delete';

    /*
     * Calendar
     */
    const CALENDAR_VIEW = 'calendar.view';

    /*
     * Meetings
     */
    const MEETINGS_VIEW = 'meetings.view';
    const MEETINGS_EDIT = 'meetings.edit';
    const MEETINGS_CREATE = 'meetings.create';
    const MEETINGS_DELETE = 'meetings.delete';

    /*
     * Reservations
     */

    const RESERVATION_VIEW = 'reservation.view';
    const RESERVATION_EDIT = 'reservation.edit';
    const RESERVATION_CREATE = 'reservation.create';
    const RESERVATION_DELETE = 'reservation.delete';
    //Special permission
    const RESERVATIONS_SPECIAL_CANCEL = 'reservation.special_create';

    /*
     * Membership
     */
    const MEMBERSHIP_VIEW = 'membership.view';
    const MEMBERSHIP_EDIT = 'membership.edit';
    const MEMBERSHIP_CREATE = 'membership.create';
    const MEMBERSHIP_DELETE = 'membership.delete';

    /*
     * Companies colors
     */

    const COLORS_VIEW = 'colors.view';
    const COLORS_EDIT = 'colors.edit';
    const COLORS_CREATE = 'colors.create';

    /*
     * Compras
     */
    const PURCHASE_CREATE = 'purchase.create';
    const PURCHASE_VIEW = 'purchase.view';

    /*
     * GiftCard
     */
    const GIFTCARD_ASSIGN = 'giftcard.assign';
    const GIFTCARD_VIEW = 'giftcard.view';
    const GIFTCARD_EDIT = 'giftcard.view';


    /*
     * Mail de remitente para notificaciones
     */
    const NOTIFICATION_EMAIL_VIEW = 'notifications_sender_email.view';
    const NOTIFICATION_EMAIL_EDIT = 'notifications_sender_email.edit';

    /*
     * PAYMENT_TYPES
     */
    const PAYMENTS_VIEW = 'payments.view';
    const PAYMENTS_CREATE = 'payments.create';
    const PAYMENTS_EDIT = 'payments.edit';

    /*
     * Mails de notificación
     */
    const MAILS_WELCOME_VIEW = 'mails_welcome.view';
    const MAILS_WELCOME_EDIT = 'mails_welcome.edit';
    const MAILS_WELCOME_MENU = 'mails_welcome.menu';
    const MAILS_RESET_PASSWORD_VIEW = 'mails_reset_password.view';
    const MAILS_RESET_PASSWORD_EDIT = 'mails_reset_password.edit';
    const MAILS_RESET_PASSWORD_MENU = 'mails_reset_password.menu';
    const MAILS_CONFIRM_RESERVATION_VIEW = 'mails_confirm_reservation.view';
    const MAILS_CONFIRM_RESERVATION_EDIT = 'mails_confirm_reservation.edit';
    const MAILS_CONFIRM_RESERVATION_MENU = 'mails_confirm_reservation.menu';
    const MAILS_RESERVATION_CANCELLED_VIEW = 'mails_reservation_cancelled.view';
    const MAILS_RESERVATION_CANCELLED_EDIT = 'mails_reservation_cancelled.edit';
    const MAILS_RESERVATION_CANCELLED_MENU = 'mails_reservation_cancelled.menu';
    const MAILS_CONFIRM_PURCHASE_VIEW = 'mails_confirm_purchase.view';
    const MAILS_CONFIRM_PURCHASE_EDIT = 'mails_confirm_purchase.edit';
    const MAILS_CONFIRM_PURCHASE_MENU = 'mails_confirm_purchase.menu';
    const MAILS_CONFIRM_WAITLIST_VIEW = 'mails_confirm_waitlist.view';
    const MAILS_CONFIRM_WAITLIST_EDIT = 'mails_confirm_waitlist.edit';
    const MAILS_CONFIRM_WAITLIST_MENU = 'mails_confirm_waitlist.menu';
    const MAILS_CANCEL_WAITLIST_VIEW = 'mails_cancel_waitlist.view';
    const MAILS_CANCEL_WAITLIST_EDIT = 'mails_cancel_waitlist.edit';
    const MAILS_CANCEL_WAITLIST_MENU = 'mails_cancel_waitlist.menu';
    const MAILS_CONFIRM_INVITATION_VIEW = 'mails_confirm_invitation.view';
    const MAILS_CONFIRM_INVITATION_EDIT = 'mails_confirm_invitation.edit';
    const MAILS_CONFIRM_SUBSCRIPTION_VIEW = 'mails_confirm_subscription.view';
    const MAILS_CONFIRM_SUBSCRIPTION_EDIT = 'mails_confirm_subscription.edit';
    const MAILS_CONFIRM_SUBSCRIPTION_MENU = 'mails_confirm_subscription.menu';
    const MAILS_ERROR_SUBSCRIPTION_VIEW = 'mails_error_subscription.view';
    const MAILS_ERROR_SUBSCRIPTION_EDIT = 'mails_error_subscription.edit';
    const MAILS_ERROR_SUBSCRIPTION_MENU = 'mails_error_subscription.menu';
    const MAILS_IMPORT_USER_VIEW = 'mails_import_user.view';
    const MAILS_IMPORT_USER_EDIT = 'mails_import_user.edit';
    const MAILS_IMPORT_USER_MENU = 'mails_import_user.menu';
    /*
     * Maps Room
     */
    const MAPS_VIEW = 'maps.view';
    const MAPS_CREATE = 'maps.create';
    const MAPS_EDIT = 'maps.edit';

    /*
     * Position Maps
     */
    const POSITIONS_VIEW = 'positions.view';
    const POSITIONS_CREATE = 'positions.create';
    const POSITIONS_EDIT = 'positions.edit';

    /*
     * Discount Code
     */
    const DISCOUNT_VIEW = 'discount.view';
    const DISCOUNT_CREATE = 'discount.create';
    const DISCOUNT_EDIT = 'discount.edit';
    const DISCOUNT_DELETE = 'discount.delete';
    /*
     * System Log
     */
    const SYSTEM_LOG_VIEW = 'system_log.view';

    /*
     * Panels
     */
    const CATALOGS_VIEW = 'catalogs.view';
    const CATALOGS_RESERVATIONS_VIEW = 'catalogs.view-reservations';
    const CATALOGS_PURCHASES_VIEW = 'catalogs.view-purchases';
    const CATALOGS_RESERVATIONS_MENU = 'catalogs.menu-reservations';
    const CATALOGS_PURCHASES_MENU = 'catalogs.menu-purchases';
    const CATALOGS_SUBSCRIPTIONS_MENU = 'catalogs.menu-subscriptions';
    const CATALOGS_SUBSCRIPTIONS_VIEW = 'catalogs.subscriptions-view';

    /*
     * Waitlist
     */
    const MENU_WAITLIST = 'menu.waitlist';
    const WAITLIST_VIEW = 'waitlist.view';
    const WAITLIST_CREATE = 'waitlist.create';
    const WAITLIST_EDIT = 'waitlist.edit';
    const WAITLIST_DELETE = 'waitlist.delete';
    const WAITLIST_MOVE_TO_OVERBOOKING = 'waitlist.move-to-overbooking';
    /*
     * Overbooking
     */
    const MENU_OVERBOOKING = 'menu.overbooking';
    const OVERBOOKING_VIEW = 'overbooking.view';
    const OVERBOOKING_CREATE = 'overbooking.create';
    const OVERBOOKING_EDIT = 'overbooking.edit';
    const OVERBOOKING_DELETE = 'overbooking.delete';

    /*
    * Textos Especiales
    */
    const SPECIAL_TEXT_VIEW = 'special_text.view';

    const ATTENDANCE_LIST_EDIT = 'attendance.edit';
//    const SPECIAL_TEXT_CREATE = 'special_text.create';
//    const SPECIAL_TEXT_EDIT = 'special_text.edit';
//    const SPECIAL_TEXT_DELETE = 'special_text.delete';


    const USER_CREDITS_EDIT = 'user_credits.edit';
    const USER_CREDITS_DELETE = 'user_credits.delete';
    const USER_MEMBERSHIP_DELETE = 'user_membership.delete';

    /*
     * Subscriptions
     */
    const SUBSCRIPTION_CANCEL = 'subscriptions.cancel';
    const USER_MEMBERSHIP_EDIT = 'user_membership.edit';

    const PRODUCTS_VIEW = 'products.view';
    const PRODUCTS_EDIT = 'products.edit';
    const PRODUCTS_DELETE = 'products.delete';
    const PRODUCTS_CREATE = 'products.create';
    const PRODUCTS_SALES = 'products.sales';

    /*
     * Business Intelligence
     */
    const BUSINESS_INTELLIGENCE_RESERVATIONS = 'business_intelligence.reservations';

    const USER_IMPORT_MENU = 'menu.user_import';
    const USER_IMPORT_VIEW = 'user_import.view';
    const USER_IMPORT_EDIT = 'user_import.edit';

    /*
     * Gympass
     */

    const GYMPASS_CHECKIN_VALIDATE_MENU = 'menu.gympass.checkin.validate';
    const GYMPASS_CHECKIN_ADMIN_MENU = 'menu.gympass.checkin.admin';

    const GYMPASS_CHECKIN_ADMIN_VIEW = 'gympass.checkin.view';
    const GYMPASS_CHECKIN_ADMIN_EDIT = 'gympass.checkin.edit';

    const GYMPASS_CHECKIN_VALIDATE_VIEW = 'gympass.checkin.validate.view';
    const GYMPASS_CHECKIN_VALIDATE_EDIT = 'gympass.checkin.validate.edit';

    const GYMPASS_CLASS_VIEW = 'gympass.class.view';
    const GYMPASS_CLASS_EDIT = 'gympass.class.edit';

    const GYMPASS_SLOT_VIEW = 'gympass.slot.view';
    const GYMPASS_SLOT_EDIT = 'gympass.slot.edit';
    const GYMPASS_SLOT_REGENERATE = 'gympass.slot.regenerate';

    const GYMPASS_USER_VIEW = 'gympass.user.view';

    const GYMPASS_BOOKING_VIEW = 'gympass.booking.view';

    const GYMPASS_SETTINGS_MENU = 'menu.gympass.settings';
    const GYMPASS_SETTINGS_VIEW = 'gympass.settings.view';
    const GYMPASS_SETTINGS_EDIT = 'gympass.settings.edit';

    const GYMPASS_LOCATION_VIEW = 'gympass.location.view';
    const GYMPASS_LOCATION_EDIT = 'gympass.location.edit';
}


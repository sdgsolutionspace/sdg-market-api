import { User } from "./user";

export interface SellOffer {
    number_of_tokens: string,
    sell_price_per_token: string,
    offer_starts_utc_date: string,
    offer_expires_at_utc_date: string,
    project: string,
    seller: User
};
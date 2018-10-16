import { User } from "./user";
import { Project } from "./project";

export interface PurchaseOffer {
    id?: number,
    number_of_tokens: string,
    purchase_price_per_token: string,
    offer_starts_utc_date: string,
    offer_expires_at_utc_date: string,
    project: Project,
    purchaser: User
};

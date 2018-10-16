import { Component, OnInit, Input, ElementRef, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiSellOfferService } from 'src/app/services/api/api-sell-offer.service';
import { ToastrService } from 'ngx-toastr';
import { SellOffer } from 'src/app/interfaces/sell-offer';
import { ApiPurchaseOfferService } from 'src/app/services/api/api-purchase-offer.service';
import { PurchaseOffer } from 'src/app/interfaces/purchase-offer';

@Component({
  selector: 'app-project-auction',
  templateUrl: './project-auction.component.html',
  styleUrls: ['./project-auction.component.scss']
})
export class ProjectAuctionComponent implements OnInit {

  title = 'GitHub Trading';
  selectedTab = 1;
  public sellForm: FormGroup;
  public purchaseOfferForm: FormGroup;
  public projectId: number;
  public sellFormSubmitted = false;
  public purchaseOfferFormSubmitted = false;
  public currentSales: Array<SellOffer> = null;
  public currentPurchaseOffers: Array<PurchaseOffer> = null;



  @ViewChild('sellModalCloseButton') sellModalCloseButton: ElementRef;
  @ViewChild('purchaseOfferModalCloseButton') purchaseOfferModalCloseButton: ElementRef;

  constructor(private fb: FormBuilder, private route: ActivatedRoute, private sellOfferApi: ApiSellOfferService, private purchaseOfferApi: ApiPurchaseOfferService, public toastr: ToastrService, public router: Router) {
  }

  private generateSellingForm() {
    this.sellFormSubmitted = false;
    this.sellForm = this.fb.group({
      number_of_tokens: [1, [
        Validators.required
      ]],
      sell_price_per_token: [1, [
        Validators.required
      ]],
      project: [this.projectId, [
        Validators.required
      ]]
    });
  }

  private generatePurchaseOfferForm() {
    this.purchaseOfferFormSubmitted = false;

    this.purchaseOfferForm = this.fb.group({
      number_of_tokens: [1, [
        Validators.required
      ]],
      purchase_price_per_token: [1, [
        Validators.required
      ]],
      project: [this.projectId, [
        Validators.required
      ]]
    });
  }

  ngOnInit() {
    this.projectId = this.route.snapshot.params["id"];
    this.generateSellingForm();
    this.generatePurchaseOfferForm();
    this.refreshSellOffer();
    this.refreshPurchasesOffer();
  }

  public refreshSellOffer() {
    this.sellOfferApi.getAll(this.projectId).toPromise().then(sales => {
      this.currentSales = sales;
      console.log("Selling offers", sales);
    });
  }

  public refreshPurchasesOffer() {
    this.purchaseOfferApi.getAll(this.projectId).toPromise().then(purchasesOffer => {
      this.currentPurchaseOffers = purchasesOffer;
      console.log("Purchases offers", purchasesOffer);
    });
  }

  public submitSellForm() {
    const formValue = this.sellForm.value;

    this.sellFormSubmitted = true;
    if (this.sellForm.valid) {
      this.sellOfferApi.create(formValue).toPromise().then((data) => {
        this.sellModalCloseButton.nativeElement.click();
        this.generateSellingForm();
        this.refreshSellOffer();
        console.log("success", data);
        this.toastr.success("The data have been saved successfully", "Data saved");
      }).catch(error => {
        console.log(error);
        this.toastr.error("An error occurred while saving your data", error);
      });
    }
  }

  public submitPurchaseOfferForm() {
    const formValue = this.purchaseOfferForm.value;
    this.purchaseOfferFormSubmitted = true;
    if (this.purchaseOfferForm.valid) {
      this.purchaseOfferApi.create(formValue).toPromise().then((data) => {
        this.purchaseOfferModalCloseButton.nativeElement.click();
        this.generatePurchaseOfferForm();
        this.refreshPurchasesOffer();
        this.toastr.success("The data have been saved successfully", "Data saved");
      }).catch(error => {
        console.log(error);
        this.toastr.error("An error occurred while saving your data", error);
      });
    }

  }

}

import { Component, OnInit, Input, ElementRef, ViewChild } from '@angular/core';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ActivatedRoute, Router } from '@angular/router';
import { ApiSellOfferService } from 'src/app/services/api/api-sell-offer.service';
import { ToastrService } from 'ngx-toastr';
import { SellOffer } from 'src/app/interfaces/sell-offer';

@Component({
  selector: 'app-project-auction',
  templateUrl: './project-auction.component.html',
  styleUrls: ['./project-auction.component.scss']
})
export class ProjectAuctionComponent implements OnInit {

  title = 'GitHub Trading';
  selectedTab = 1;
  public sellForm: FormGroup;
  public projectId: number;
  public sellFormSubmitted = false;
  public currentSales: Array<SellOffer> = null;

  @ViewChild('sellModalCloseButton') sellModalCloseButton: ElementRef;

  constructor(private fb: FormBuilder, private route: ActivatedRoute, private sellOfferApi: ApiSellOfferService, public toastr: ToastrService, public router: Router) {
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

  ngOnInit() {
    this.projectId = this.route.snapshot.params["id"];
    this.generateSellingForm();
    this.refreshSellOffer();
  }

  public refreshSellOffer() {
    this.sellOfferApi.getAll(this.projectId).toPromise().then(sales => {
      this.currentSales = sales;
      console.log(sales);
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

}

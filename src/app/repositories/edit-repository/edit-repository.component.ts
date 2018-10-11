import { Component, OnInit } from '@angular/core';
import { GitProjectApiService } from 'src/app/services/api/git-project-api.service';
import { GitProject } from 'src/app/interfaces/git-project';
import { ActivatedRoute } from '@angular/router';
import { FormBuilder, FormGroup, Validators } from '@angular/forms';
import { ToastrService } from 'ngx-toastr';
import { Router } from "@angular/router";

@Component({
  selector: 'app-edit-repository',
  templateUrl: './edit-repository.component.html',
  styleUrls: ['./edit-repository.component.scss']
})
export class EditRepositoryComponent implements OnInit {

  public title = 'GitHub Trading';
  public project: GitProject;
  public form: FormGroup;
  public loading = false;
  public projectId: number;
  public submitted = false;

  constructor(private projectsApi: GitProjectApiService, private route: ActivatedRoute, private fb: FormBuilder, public toastr: ToastrService, public router: Router) {
  }

  ngOnInit() {
    const urlRegexp = '(https?://)?([\\da-z.-]+)\\.([a-z.]{2,6})[/\\w .-]*/?';

    this.projectId = this.route.snapshot.params["id"]
    this.projectsApi.get(this.projectId).subscribe((project) => {
      this.project = project;
      this.form = this.fb.group({
        "name": [this.project.name, [
          Validators.required,
        ]],
        "git_address": [this.project.git_address, [
          Validators.required,
          Validators.pattern(urlRegexp)
        ]],
        "project_address": [this.project.project_address, [
          Validators.required,
          Validators.pattern(urlRegexp)
        ]],
        "active": [this.project.active, [
          Validators.required,
        ]]
      });
    });
  }

  async submitForm() {
    const formValue = this.form.value;
    this.submitted = true;

    if (this.form.valid) {
      this.projectsApi.update(this.projectId, formValue).toPromise().then((data) => {
        console.log("success", data);
        this.toastr.success("The data have been saved successfully", "Data saved");
        this.router.navigate(["/repositories"]);
      }).catch(error => {
        console.log(error);
        this.toastr.error("An error occurred while saving your data", error);
      });
    }

  }

}

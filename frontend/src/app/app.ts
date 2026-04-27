import { Component, OnInit } from '@angular/core';
import { Api, ImportResponse } from './services/api';
@Component({
  selector: 'app-root',
  templateUrl: './app.html',
  standalone: false,
  styleUrl: './app.css',
})
export class App implements OnInit {
  clients: any[] = [];
  searchTerm: string = '';

  constructor(private api: Api) {}

  ngOnInit(): void {
    this.search();
    this.loadInvoices();
  }
  search() {
    const term = this.searchTerm ? this.searchTerm.trim() : '';

    if (term === '') {
      this.clients = [];
      return;
    }
    this.api.getClients(term).subscribe({
      next: (data) => {
        this.clients = data;
      },
    });
  }
  loadInvoices() {
    this.api.getInvoices().subscribe((data) => (this.invoices = data));
  }

  invoices: any[] = [];
  selectClientId: string = '';

  newInvoice = {
    clientId: '',
    items: [{ description: '', quantity: 1, unitPrice: 0 }],
  };
  addItem() {
    this.newInvoice.items.push({ description: '', quantity: 1, unitPrice: 0 });
  }

  msg: string = '';
  clientError: boolean = false;

  saveInvoice() {
    this.msg = '';
    this.clientError = false;

    if (!this.newInvoice.clientId) {
      this.msg = 'Please select a client first';
      this.clientError = true;
      setTimeout(() => {
        this.clientError = false;
      }, 3000);
      return;
    }
    this.api.createInvoices(this.newInvoice).subscribe({
      next: (res) => {
        alert('Invoice Created Successfully!');
        this.loadInvoices();
        this.newInvoice = { clientId: '', items: [{ description: '', quantity: 1, unitPrice: 0 }] };
      },
    });
  }
  onFileSelected(event: any) {
    const file: File = event.target.files[0];
    if (file) {
      const formData = new FormData();
      formData.append('file', file);
      this.api.importInvoices(formData).subscribe({
        next: (res: ImportResponse) => {
          alert(`Success: ${res.created} created. Errors:${res.errors.length}`);
          this.loadInvoices();
        },
        error: (err) => alert('Import Failed'),
      });
    }
  }
}

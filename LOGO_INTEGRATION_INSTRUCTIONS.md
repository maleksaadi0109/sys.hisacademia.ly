# Company Logo Integration Instructions

## How to Add Your Company Logo

### Step 1: Save Your Logo
1. **Save your logo file** as `company_logo.png` in the `public/images/` directory
2. **Recommended size**: 200x200 pixels or larger (square format works best)
3. **Format**: PNG with transparent background (preferred) or JPG

### Step 2: File Location
```
public/images/logo.jpg
```

### Step 3: Logo Requirements
- **Format**: PNG, JPG, or SVG
- **Size**: Minimum 200x200 pixels
- **Background**: Transparent PNG preferred
- **Quality**: High resolution for print

### Step 4: Test the Logo
1. **Your logo is already set** at `public/images/logo.jpg`
2. **Create a booking** using the form
3. **Check the receipt** - logo should appear automatically

### Step 5: Alternative Logo Names
If you want to use a different filename, update the controller:
```php
// In app/Http/Controllers/DataEntry/DataEntryController.php
'companyLogo' => asset('images/your_logo_name.png'),
```

### Current Company Information
- **Company Name**: الأكاديمية الأسبانية الليبية
- **Tagline**: مساحات العمل المشتركة
- **Logo Path**: `public/images/logo.jpg`

### Logo Display
The logo will appear:
- **In the receipt header** (circular frame)
- **Automatically resized** to fit the A6 format
- **With proper styling** and shadows

### Troubleshooting
- **Logo not showing?** Check file path and permissions
- **Logo too big/small?** Resize to 200x200 pixels
- **Logo blurry?** Use higher resolution image
- **Wrong format?** Convert to PNG with transparent background

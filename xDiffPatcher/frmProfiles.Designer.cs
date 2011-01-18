namespace xDiffPatcher
{
    partial class frmProfiles
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// Clean up any resources being used.
        /// </summary>
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.lstProfiles = new System.Windows.Forms.ListBox();
            this.grpProfile = new System.Windows.Forms.GroupBox();
            this.txtPatches = new System.Windows.Forms.TextBox();
            this.lblProfile = new System.Windows.Forms.Label();
            this.btnApply = new System.Windows.Forms.Button();
            this.btnDelete = new System.Windows.Forms.Button();
            this.grpProfile.SuspendLayout();
            this.SuspendLayout();
            // 
            // lstProfiles
            // 
            this.lstProfiles.Anchor = ((System.Windows.Forms.AnchorStyles)(((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)));
            this.lstProfiles.FormattingEnabled = true;
            this.lstProfiles.HorizontalScrollbar = true;
            this.lstProfiles.ItemHeight = 16;
            this.lstProfiles.Location = new System.Drawing.Point(13, 13);
            this.lstProfiles.Margin = new System.Windows.Forms.Padding(4);
            this.lstProfiles.Name = "lstProfiles";
            this.lstProfiles.Size = new System.Drawing.Size(254, 372);
            this.lstProfiles.TabIndex = 0;
            this.lstProfiles.SelectedIndexChanged += new System.EventHandler(this.lstProfiles_SelectedIndexChanged);
            // 
            // grpProfile
            // 
            this.grpProfile.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.grpProfile.Controls.Add(this.btnDelete);
            this.grpProfile.Controls.Add(this.btnApply);
            this.grpProfile.Controls.Add(this.lblProfile);
            this.grpProfile.Controls.Add(this.txtPatches);
            this.grpProfile.Location = new System.Drawing.Point(274, 13);
            this.grpProfile.Name = "grpProfile";
            this.grpProfile.Size = new System.Drawing.Size(387, 372);
            this.grpProfile.TabIndex = 1;
            this.grpProfile.TabStop = false;
            // 
            // txtPatches
            // 
            this.txtPatches.Anchor = ((System.Windows.Forms.AnchorStyles)((((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Bottom)
                        | System.Windows.Forms.AnchorStyles.Left)
                        | System.Windows.Forms.AnchorStyles.Right)));
            this.txtPatches.Location = new System.Drawing.Point(9, 38);
            this.txtPatches.Multiline = true;
            this.txtPatches.Name = "txtPatches";
            this.txtPatches.ReadOnly = true;
            this.txtPatches.Size = new System.Drawing.Size(246, 328);
            this.txtPatches.TabIndex = 0;
            // 
            // lblProfile
            // 
            this.lblProfile.AutoSize = true;
            this.lblProfile.Location = new System.Drawing.Point(6, 18);
            this.lblProfile.Name = "lblProfile";
            this.lblProfile.Size = new System.Drawing.Size(63, 17);
            this.lblProfile.TabIndex = 1;
            this.lblProfile.Text = "Patches:";
            // 
            // btnApply
            // 
            this.btnApply.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnApply.Location = new System.Drawing.Point(261, 38);
            this.btnApply.Name = "btnApply";
            this.btnApply.Size = new System.Drawing.Size(120, 25);
            this.btnApply.TabIndex = 2;
            this.btnApply.Text = "Apply";
            this.btnApply.UseVisualStyleBackColor = true;
            this.btnApply.Click += new System.EventHandler(this.btnApply_Click);
            // 
            // btnDelete
            // 
            this.btnDelete.Anchor = ((System.Windows.Forms.AnchorStyles)((System.Windows.Forms.AnchorStyles.Top | System.Windows.Forms.AnchorStyles.Right)));
            this.btnDelete.Location = new System.Drawing.Point(261, 69);
            this.btnDelete.Name = "btnDelete";
            this.btnDelete.Size = new System.Drawing.Size(120, 25);
            this.btnDelete.TabIndex = 3;
            this.btnDelete.Text = "Delete";
            this.btnDelete.UseVisualStyleBackColor = true;
            this.btnDelete.Click += new System.EventHandler(this.btnDelete_Click);
            // 
            // frmProfiles
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(8F, 16F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(673, 397);
            this.Controls.Add(this.grpProfile);
            this.Controls.Add(this.lstProfiles);
            this.Margin = new System.Windows.Forms.Padding(4);
            this.Name = "frmProfiles";
            this.Text = "Profiles";
            this.Load += new System.EventHandler(this.frmProfiles_Load);
            this.Shown += new System.EventHandler(this.frmProfiles_Shown);
            this.grpProfile.ResumeLayout(false);
            this.grpProfile.PerformLayout();
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.ListBox lstProfiles;
        private System.Windows.Forms.GroupBox grpProfile;
        private System.Windows.Forms.Button btnDelete;
        private System.Windows.Forms.Button btnApply;
        private System.Windows.Forms.Label lblProfile;
        private System.Windows.Forms.TextBox txtPatches;
    }
}